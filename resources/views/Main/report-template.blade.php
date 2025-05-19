<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plagiarism Report</title>
    <!-- <link rel="stylesheet" href="{{ asset('css/plag-report.css') }}"> -->
     <style>
        * {
    margin: 0;
    padding: 0;
    font-family: "Poppins", sans-serif;
    /* box-sizing: border-box; */
}

nav {
    height: 85px;
    width: 100%;
    /* background-color:#4A4947; */
    background-color:#005F40;

    /* padding-left: 40px;
    padding-right: 40px; */
    display: flex;
    justify-content: center;
    z-index: 1;
    color: aliceblue;
}

.nav-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 100%;
    width: 87%;
    /* background-color: blue; */
}

.nav-container img {
    height: 80px;
    width: 110px;
}

.nav-container .download-report {
    height: 39px;
    width: 160px;
    border: 1px solid transparent;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
    font-weight:600;
}

.nav-container .download-report:hover {
    background-color: rgb(85, 122, 85);
    color: white;
}
.main-content {
    margin: auto;
    border: 2px solid rgb(85, 122, 85);
    border-radius: 5px;
    height:auto;
    width: 87%;
    margin-top: 23px;
}
.report-plag{
    display: flex;
    /* background-color: #009933; */
    margin-bottom: 30px;
    width: 100%;
}
.report-info{
    height: 259px;
    width: 50%;
    border-right: 2px solid #009933;
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 20px;
    padding-left: 20px;
    font-weight: normal;
    color: black;
}
.plagiarism-content {
    padding-top: 20px;
    padding-bottom: 40px;
    padding-left: 40px;
    width: 50%;
}
.plagiarism-content h2{
    text-align: center;
}
.not-found{
    display: flex;
    justify-content: center;
    align-items: center;
}
.not-found p{
    font-size: 18px;
    margin: auto;
    font-weight: bold;
}
span{
    font-size: 16px;
    font-weight: normal;
}

.matched-sources-section {
    background-color: #b93d3d;
    padding: 20px;
    border-radius: 10px;
    height: 230px;
    box-sizing: border-box;
    width: 98%;
    margin: auto;
}

.matched-sources-section h3 {
    text-align: center;
    color:aliceblue;
    margin-bottom: 15px;
}

.matched-sources {
    background-color: #ffffff;
    max-height: 150px;
    overflow-y: auto;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-sizing: border-box;
}
.matched-sources ul {
    list-style: none;
    padding: 0;
}

.matched-sources li {
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #ddd;
    display: flex;
    justify-content:space-between;
    align-items: center;
    padding-left: 10px;
    height: 50px;
}
.matched-sources .name{
    width: 200px;
}
.matched-sources .file-name{
    width: 315px;
}
.matched-sources .similarity{
    width: 170px;
}


.similarity-box-wrapper {
    position: relative;
    top: 20px;
    right: 10px;
    display: flex;
    gap: 40px;
    z-index: 1;
}

.similarity-box {
    display: flex;
    gap: 40px;
    justify-content: center;
    align-items: center;
    padding-top: 8px;
    flex-direction:column;
}

.similarity-item {
    background: #f9f9f9;
    padding: 10px;
    border-radius: 25px;
    height: 80px;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    font-family: 'Segoe UI', sans-serif;
    font-weight: bold;
    transition: transform 0.3s ease;
    min-width: 150px;
    margin-bottom:20px;

}

.circle-chart {
    width: 100px;
    height: 100px;
}

.circle-bg {
    fill: none;
    stroke: #eee;
    stroke-width: 3.8;
}

.circle {
    fill: none;
    stroke: #00b8d9;
    stroke-width: 2.8;
    stroke-linecap: round;
    transform: rotate(-90deg);
    transform-origin: center;
    transition: stroke-dasharray 0.6s ease;
}

.originality-stroke {
    stroke: #00c851;
}

.percentage-text {
    fill: #333;
    font-size: 7px;
    text-anchor: middle;
    font-weight: bold;
}

.similarity:hover {
    background-color: #ffe0e0;
    transform: scale(1.05);
    color: #d10000;
}

.originality:hover {
    background-color: #e0ffe6;
    transform: scale(1.05);
    color: #009933;
}

.similarity-item p {
    margin: 5px 0 0;
    font-size: 14px;
    color: #444;
}


.feedback-section {
    margin-top: 40px;
    background: #fefefe;
    height:500px;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.05);
}

.feedback-section h3 {
    font-size: 22px;
    margin-bottom: 10px;
    color: #333;
}

.feedback-box {
    font-size: 16px;
    color: #444;
    background-color: #fff;
    padding: 20px;
    border-left: 5px solid #00b8d9;
    border-radius: 10px;
    line-height: 1.7;
    overflow: visible;
    word-wrap: break-word;
    height: auto;
}

.feedback-box ul {
    margin: 10px 0 0 15px;
    padding: 0;
    list-style-type: disc;
}

.feedback-box li {
    margin-bottom: 8px;
}


     </style>
</head>
<body>
    <div class="main-content">
        <div class="report-plag">
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
                <div class="similarity-box">
                    <div class="similarity-item similarity">
                        <svg class="circle-chart" viewBox="0 0 36 36">
                            <path class="circle-bg" d="M18 2.0845
                     a 15.9155 15.9155 0 0 1 0 31.831
                     a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path class="circle" stroke-dasharray="{{ $selectedSimilarity }}, 100" d="M18 2.0845
                     a 15.9155 15.9155 0 0 1 0 31.831
                     a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <text x="18" y="20.35" class="percentage-text">{{ $selectedSimilarity }}%</text>
                        </svg>
                        <p>Similarity</p>
                    </div>
                    <div class="similarity-item originality">
                        <svg class="circle-chart" viewBox="0 0 36 36">
                            <path class="circle-bg" d="M18 2.0845
                     a 15.9155 15.9155 0 0 1 0 31.831
                     a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path class="circle originality-stroke"
                                stroke-dasharray="{{ $selectedOriginality }}, 100" d="M18 2.0845
                     a 15.9155 15.9155 0 0 1 0 31.831
                     a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <text x="18" y="20.35" class="percentage-text">{{ $selectedOriginality }}%</text>
                        </svg>
                        <p>Originality</p>
                    </div>
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
                            <strong>Student:</strong> {{ $result['student_name'] ?? 'Unknown' }}<br>
                            <strong>File:</strong> {{ $result['file_name'] }}<br>
                            <strong>Matched %:</strong> {{ $result['similarity'] }}%
                        </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>

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
</body>
</html>
