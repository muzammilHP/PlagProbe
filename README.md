# PlagProbe

PlagProbe is an advanced plagiarism detection system designed for educational use, helping teachers and students ensure originality in assignments. It supports multiple assignment types, including text documents, handwritten work, programming code, and presentations, providing comprehensive similarity reports.

**Technologies:** Laravel, MySQL, JavaScript, Python, AI Algorithms

**My role:** Developed the full-stack web application including both front-end and back-end using Laravel and JavaScript, integrated AI-powered plagiarism detection algorithms, and designed the user interface for teachers and students.

**Features:**
- Upload and manage various types of assignments (text, handwritten, code, presentations)
- Automated text extraction and plagiarism comparison
- AI tone detection to identify AI-generated content
- Student clustering based on similarity for in-depth analysis
- Separate panels for teachers and students with role-based access
- Detailed plagiarism reports and analytics
- Admin panel to manage users, classes, and system settings

**Demo:** https://youtu.be/vHh1caphJFg

**Installation:**
1. Clone the repository
2. Run `composer install` to install PHP dependencies
3. Set up your `.env` file based on `.env.example`
4. Run database migrations with `php artisan migrate`
5. Seed initial data if applicable with `php artisan db:seed`
6. Serve the application with `php artisan serve`
7. Configure Python environment and dependencies for AI algorithms as per documentation

