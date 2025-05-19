<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plagiarism Report</title>
    <link rel="stylesheet" href="{{ asset('css/plag-report.css') }}">
</head>

<body>
    <div class="container">
    @php
    use App\Models\Report;

    $reportJustSaved = session('report_saved') 
                       && session('saved_assignment_id') == $assignmentId 
                       && session('saved_student_email') == $studentEmail;

    $existingReport = Report::where('student_email', $studentEmail)
                            ->where('assignment_id', $assignmentId)
                            ->first();

    $reportIsSaved = $reportJustSaved || $existingReport;
@endphp
        <nav>
            <div class="nav-container">
                <img src="{{ asset('images/Logo.png') }}" alt="PlagProbe Logo">
                <h2>Plagiarism Checking Report</h2>
                <div class="nav-right">

                <form action="{{ route('report.sendEmail') }}" method="POST" onsubmit="this.querySelector('.share-report').innerText = 'Shared'; this.querySelector('.share-report').classList.add('shared'); this.querySelector('.share-report').disabled = true;">
    @csrf
    <input type="hidden" name="student_email" value="{{ $studentEmail ?? '' }}">
    <input type="hidden" name="student_id" value="{{ $studentId ?? '' }}">
    <input type="hidden" name="student_name" value="{{ $studentName ?? 'Student' }}">
    <input type="hidden" name="assignment_id" value="{{ $assignmentId ?? '' }}">
    <input type="hidden" name="report_data" value="{{ base64_encode($reportHtml) }}">
    <button type="submit" class="share-report">Share Report</button>
</form>

                 <form action="{{ route('report.download') }}" method="POST" onsubmit="this.querySelector('.download-report').innerText = 'Downloaded'; this.querySelector('.download-report').classList.add('saved'); this.querySelector('.download-report').disabled = true;">
    @csrf
    <input type="hidden" name="student_name" value="{{ $studentName ?? 'student' }}">
    <input type="hidden" name="student_id" value="{{ $studentId ?? '' }}">
    <input type="hidden" name="assignment_id" value="{{ $assignmentId ?? '' }}">
    <input type="hidden" name="report_data" value="{{ base64_encode($reportHtml) }}">
    <button type="submit" class="download-report">Download Report</button>
</form>

                <form action="{{ route('report.save') }}" method="POST">
                @csrf
                <input type="hidden" name="student_name" value="{{ $studentName ?? 'Student' }}">
                <input type="hidden" name="student_id" value="{{ $studentId ?? '' }}">
                <input type="hidden" name="student_email" value="{{ $studentEmail ?? '' }}">
                <input type="hidden" name="assignment_id" value="{{ $assignmentId ?? '' }}">
                <input type="hidden" name="report_data" value="{{ base64_encode($reportHtml) }}">
                <button type="submit"
    class="save-report {{ $reportIsSaved ? 'saved' : '' }}"
    {{ $reportIsSaved ? 'disabled' : '' }}>
    {{ $reportIsSaved ? 'Saved' : 'Save Report' }}
</button>
                </form>

                </div>
            </div>
        </nav>

        @if(session('error'))
        <div class="alert-message error-alert">
            <strong>Error:</strong> {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert-message success-alert">
            <strong>Success:</strong> {{ session('success') }}
        </div>
    @endif

    @if (session('downloaded'))
    <div class="alert-message success-alert">
        <strong>Success:</strong> {{ session('downloaded') }}
    </div>
    @endif

    @php
    $similarity = $selectedSimilarity ?? 0;
@endphp

        <div class="main-content">
            <div class="report-info">
                <h3>Student Name: <span>{{ $studentName ?? 'N/A' }}</span></h3>
                <h3>Student ID: <span>{{ $studentId ?? 'N/A' }}</span></h3>
                <h3>Course: <span>{{ $course ?? 'N/A' }}</span></h3>
                <h3>Assignment Title: <span>{{ $assignmentTitle ?? 'N/A' }}</span></h3>
                <h3>Date & Time: <span>{{ now()->format('d M Y - h:i:s A') }}</span></h3>
            </div>

            <div class="plagiarism-content">
                @if(!empty($results))
                <div class="similarity-box-wrapper">
  <div class="similarity-item">
    <svg class="circle-chart" viewBox="0 0 36 36">
      <!-- Grey track behind -->
      <circle class="circle-bg" cx="18" cy="18" r="15.9155" />
      <!-- Red progress arc -->
      <circle 
        class="circle-progress" 
        cx="18" cy="18" r="15.9155"
        stroke-dasharray="{{ $similarity }}, 100" />
    </svg>
    <div class="percentage-label">
      <span class="number">{{ $similarity }}<sup>%</sup></span>
      <span class="text">Similarity</span>
    </div>
  </div>
</div>



            </div>
            <div class="matched-sources-section">
                <h3>Text Matches these sources</h3>
                <div class="matched-sources">
                    <ul>
                        @foreach($results as $result)
                        @if($result['similarity'] > 0)
                        <li>
                            <div class="name">
                                <strong>Student:</strong> {{ $result['student_name'] ?? 'Unknown' }}<br>
                            </div>
                            <div class="file-name">
                                <strong>File:</strong> {{ $result['file_name'] }}<br>
                            </div>
                            <div class="similarity">
                                <strong>Matched %:</strong> {{ $result['similarity'] }}%
                            </div>
                        </li>
                        @endif
                        @endforeach
                    </ul>
                </div>
            </div>
            <!-- Feedback and Suggestions Section -->
            <div class="feedback-section">
                <h3>Feedback & Suggestions</h3>
                <div class="feedback-box">
                    @php
                    $matchCount = collect($results)->filter(function($result) {
                    return $result['similarity'] > 0;
                    })->count();
                    @endphp

                    @if($selectedSimilarity <= 20 && $selectedOriginality>= 80 && $matchCount <= 1) <p><strong>Category:
                                Excellent</strong></p>
                            <p>Your submission demonstrates exceptional originality and academic integrity. With only {{
                                $matchCount }} minor match{{ $matchCount !== 1 ? 'es' : '' }}, you've shown strong
                                skills in paraphrasing and proper referencing.</p>
                            <ul>
                                <li>Keep citing all your sources accurately and consistently.</li>
                                <li>Continue using your own words to express researched ideas effectively.</li>
                                <li>Maintain this high standard in future submissions by double-checking for accidental
                                    overlaps, even minor ones.</li>
                            </ul>

                            @elseif($selectedSimilarity > 20 && $selectedSimilarity <= 50) <p><strong>Category:
                                    Good</strong></p>
                                <p>Your similarity score is {{ $selectedSimilarity }}%, with content overlapping with {{
                                    $matchCount }} source{{ $matchCount !== 1 ? 's' : '' }}. While still within
                                    acceptable range, there's room for improvement in paraphrasing and citation
                                    practices.</p>
                                <ul>
                                    <li>Review your work carefully, especially the parts that may resemble source
                                        material, even if not explicitly shown.</li>
                                    <li>Rephrase closely matched content in your own words to improve originality.</li>
                                    <li>Use direct quotes sparingly, and ensure they are properly cited using quotation
                                        marks and references.</li>
                                </ul>

                                @elseif($selectedSimilarity > 50 && $selectedSimilarity <= 75) <p><strong>Category:
                                        Needs Improvement</strong></p>
                                    <p>Your report shows a high similarity score of {{ $selectedSimilarity }}%, with {{
                                        $matchCount }} significant match{{ $matchCount !== 1 ? 'es' : '' }}. This
                                        suggests an overreliance on external sources or insufficient paraphrasing.</p>
                                    <ul>
                                        <li>Re-express any potentially copied content using your own voice and
                                            understanding.</li>
                                        <li>Reduce direct copying; instead, summarize or paraphrase ideas while still
                                            crediting the sources.</li>
                                        <li>If unsure about proper citation, consider seeking support from academic
                                            resources or writing centers.</li>
                                    </ul>

                                    @elseif($selectedSimilarity > 75)
                                    <p><strong>Category: Critical</strong></p>
                                    <p>Your similarity score is critically high at {{ $selectedSimilarity }}%, with
                                        matches found across {{ $matchCount }} source{{ $matchCount !== 1 ? 's' : '' }}.
                                        This level of overlap may indicate plagiarism or heavy copying, and it poses a
                                        risk of academic misconduct.</p>
                                    <ul>
                                        <li>Rework your submission immediately, replacing all matched or copied content
                                            with original phrasing.</li>
                                        <li>Ensure every idea not your own is either quoted properly or paraphrased with
                                            correct citation.</li>
                                        <li>Seek guidance from your academic supervisor or advisor before final
                                            submission.</li>
                                    </ul>
                                    @endif
                </div>
            </div>


            @else
            <div class="not-found">
                <p>No other submissions found for comparison.</p>
            </div>
            @endif
        </div>
    </div>
    </div>

    <script>
    setTimeout(() => {
        document.querySelectorAll('.alert-message').forEach(alert => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => alert.remove(), 500);
        });
    }, 4000);
</script>

</body>

</html>