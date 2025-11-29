# VoteEase - Online Voting System

**Your Voice, Your Vote. Simplified.**

A secure, modern web-based voting platform built with PHP, MySQL, and vanilla JavaScript. VoteEase provides a complete solution for managing online elections with real-time results, user management, and comprehensive security features.

---

## Features

### For Voters
- **Secure Registration & Login** - Email-based authentication with encrypted password storage
- **Real-time Voting** - Cast votes for multiple positions in active elections
- **Live Results Dashboard** - View election results as they happen
- **Profile Management** - Update personal information and view voting history
- **Vote Tracking** - See which positions you've voted for with timestamps
- **Responsive Design** - Modern, mobile-friendly interface with smooth animations

### For Administrators
- **Election Management** - Create and configure elections with custom titles, descriptions, and date ranges
- **Candidate Management** - Add, edit, archive, restore, and permanently delete candidates
- **Voter Management** - Monitor registered voters, suspend/unsuspend accounts, view suspension logs
- **Live Results Monitoring** - Real-time vote counts and election analytics
- **Vote Reset** - Clear all votes to restart elections when needed
- **Archive System** - Soft delete functionality for candidates and voters with restore capability

---

## Tech Stack

### Frontend
- **HTML5/CSS3** - Semantic markup with modern CSS features
- **Vanilla JavaScript** - No frameworks, pure ES6+ JavaScript
- **SweetAlert2** - Beautiful, customizable alert modals
- **Tailwind CSS** - Utility-first CSS framework (CDN)
- **Inter Font** - Clean, professional typography

### Backend
- **PHP 8.x** - Server-side logic and API endpoints
- **MySQL/MariaDB** - Relational database management
- **PHPMailer** - Email notifications for registration and contact forms
- **JSON API** - RESTful communication between frontend and backend

### Security
- **Password Hashing** - Bcrypt/SHA encryption for user credentials
- **SQL Injection Prevention** - Prepared statements and parameterized queries
- **CORS Headers** - Proper cross-origin resource sharing configuration
- **Session Management** - localStorage for client-side session handling
- **Input Validation** - Frontend and backend validation for all user inputs

---

## Project Structure

```
voting_system/
│
├── index.html                      # Landing page with login/registration
├── dashboard.html                  # User voting dashboard
├── admindashboard.html            # Admin control panel
│
├── asset/
│   ├── database/
│   │   └── voting_system.sql      # Database schema and sample data
│   ├── images/                    # Image assets
│   └── style/                     # CSS stylesheets
│       ├── index.css
│       ├── dashboard.css
│       ├── admindb.css
│       ├── header.css
│       └── about.css
│
├── backend/
│   ├── db.php                     # Database connection configuration
│   ├── login.php                  # User authentication endpoint
│   ├── register.php               # User registration with email verification
│   ├── email.php                  # PHPMailer email sender class
│   ├── contact.php                # Contact form handler
│   ├── profile.php                # User profile update endpoint
│   │
│   ├── # Voting APIs
│   ├── get_candidates.php         # Fetch active candidates
│   ├── cast_vote.php              # Submit votes for election
│   ├── get_votes_count.php        # Get vote counts per candidate
│   ├── get_live_results.php       # Real-time election results
│   ├── get_user_votes.php         # User's voting history
│   ├── get_user.php               # Fetch user profile data
│   │
│   ├── # Admin - Candidate Management
│   ├── add_candidate.php          # Create new candidate
│   ├── edit_candidate.php         # Update candidate information
│   ├── delete_candidate.php       # Archive candidate (soft delete)
│   ├── restore_candidate.php      # Restore archived candidate
│   ├── permanently_delete_candidate.php  # Hard delete candidate
│   ├── get_archived_candidates.php       # Fetch archived candidates
│   │
│   ├── # Admin - Voter Management
│   ├── get_voters.php             # Fetch all registered voters
│   ├── delete_voter.php           # Archive voter (soft delete)
│   ├── restore_voter.php          # Restore archived voter
│   ├── permanently_delete_voter.php      # Hard delete voter
│   ├── suspend_voter.php          # Suspend voter account
│   ├── unsuspend_voter.php        # Unsuspend voter account
│   ├── suspension_logs.php        # Fetch suspension history
│   ├── get_archived_voters.php    # Fetch archived voters
│   │
│   ├── # Admin - Election Settings
│   ├── get_election_settings.php  # Fetch current election configuration
│   ├── save_election_settings.php # Update election settings
│   ├── reset_votes.php            # Clear all votes in system
│   │
│   └── PHPMailer/                 # Email library
│       ├── PHPMailer.php
│       ├── SMTP.php
│       └── Exception.php
│
└── public/
    └── user/
        ├── profile.html           # User profile page
        ├── vote.html              # Voting interface
        ├── about.html             # About VoteEase page
        ├── contactus.html         # Contact form
        └── privacypolicy.html     # Privacy policy page
```

---

## Database Schema

### Tables

#### `voters`
- `id` - Primary key
- `name` - Full name (format: Last, First M)
- `email` - Unique email address
- `password` - Hashed password
- `address` - Physical address
- `phone` - Contact number
- `age` - Voter age
- `status` - ENUM ('active', 'suspended')
- `created_at` - Registration timestamp

#### `candidates`
- `id` - Primary key
- `name` - Candidate full name
- `position` - Running position (President, Vice President, etc.)
- `photo` - Profile photo filename (default: default_photo.jpg)
- `bio` - Candidate biography
- `is_active` - Soft delete flag (1 = active, 0 = archived)
- `created_at` - Record creation timestamp

#### `votes`
- `id` - Primary key
- `user_id` - Foreign key to voters
- `candidate_id` - Foreign key to candidates
- `position` - Position voted for
- `voted_at` - Vote timestamp

#### `election_settings`
- `id` - Primary key
- `election_title` - Election name
- `election_description` - Election details
- `start_date` - Election start date
- `end_date` - Election end date
- `is_active` - Election status (0 = inactive, 1 = active)
- `created_at` - Settings creation timestamp

#### `suspension_logs`
- `id` - Primary key
- `voter_id` - Foreign key to voters
- `reason` - Suspension reason
- `suspended_by` - Admin ID who suspended
- `suspended_at` - Suspension timestamp

---

## Installation

### Prerequisites
- **XAMPP/WAMP/MAMP** - Apache + MySQL + PHP environment
- **PHP 8.0+** - Required for modern PHP features
- **MySQL 5.7+ or MariaDB 10.4+** - Database server
- **Web Browser** - Chrome, Firefox, Edge, Safari (latest versions)

### Setup Steps

1. **Clone or Download the Project**
   ```bash
   cd C:\xampp\htdocs
   git clone <repository-url> voting_system
   # OR extract the ZIP file to C:\xampp\htdocs\voting_system
   ```

2. **Database Setup**
   - Open phpMyAdmin: `http://localhost/phpmyadmin`
   - Create a new database named `voting_system`
   - Import the SQL file:
     - Click on `voting_system` database
     - Go to **Import** tab
     - Choose file: `asset/database/voting_system.sql`
     - Click **Go** to import

3. **Configure Database Connection**
   - Open `backend/db.php`
   - Update credentials if needed:
     ```php
     $host = "localhost";
     $user = "root";
     $password = "";  // Your MySQL password
     $database = "voting_system";
     ```

4. **Configure Email Settings (Optional)**
   - Open `backend/email.php`
   - Update SMTP credentials:
     ```php
     $this->mail->Username = 'your-email@gmail.com';
     $this->mail->Password = 'your-app-password';
     ```
   - For Gmail: Enable 2FA and generate an App Password

5. **Start Apache & MySQL**
   - Open XAMPP Control Panel
   - Click **Start** for Apache and MySQL modules

6. **Access the Application**
   - Open browser and navigate to: `http://localhost/voting_system/`
   - Register a new account or use sample credentials from database

---

## Default Admin Access

The system uses the first registered user as admin. To set up admin access:

1. Register the first account through the registration form
2. This account will have admin privileges
3. Access admin dashboard: `http://localhost/voting_system/admindashboard.html`

Or use sample data from the SQL dump (if available).

---

## Usage Guide

### For Voters

1. **Register an Account**
   - Go to the homepage
   - Click "Create Account"
   - Fill in: Name (Last, First M), Email, Password, Address, Phone, Age
   - Submit and check email for welcome message

2. **Login**
   - Enter registered email and password
   - Click "Login"
   - You'll be redirected to the dashboard

3. **Cast Your Vote**
   - Click "Vote Now" from dashboard
   - Select one candidate per position
   - Review your selections
   - Click "Submit Vote"
   - Confirm submission

4. **View Results**
   - Return to dashboard
   - Scroll to "Live Election Results" section
   - See real-time vote counts and percentages

5. **Manage Profile**
   - Click profile icon or "Profile" in menu
   - Update personal information
   - View voting history

### For Administrators

1. **Access Admin Dashboard**
   - Login with admin credentials
   - Navigate to `admindashboard.html`

2. **Manage Elections**
   - Go to "Election Settings" tab
   - Set election title and description
   - Configure start and end dates
   - Toggle election active/inactive status

3. **Manage Candidates**
   - Click "Candidates" tab
   - Add new candidate with name, position, photo, bio
   - Edit existing candidates
   - Archive/restore candidates
   - View archived candidates

4. **Manage Voters**
   - Click "Voters" tab
   - View all registered voters
   - Suspend/unsuspend accounts
   - View suspension logs
   - Archive/restore voter accounts

5. **Monitor Results**
   - View live vote counts
   - Export results (if implemented)
   - Reset votes when needed (use with caution)

---

## API Endpoints

### Authentication
- `POST /backend/register.php` - Register new voter
- `POST /backend/login.php` - Authenticate user
- `POST /backend/profile.php` - Update user profile
- `GET /backend/get_user.php` - Get user details

### Voting
- `GET /backend/get_candidates.php` - Fetch active candidates
- `POST /backend/cast_vote.php` - Submit votes
- `GET /backend/get_votes_count.php` - Get vote counts
- `GET /backend/get_live_results.php` - Get real-time results
- `GET /backend/get_user_votes.php` - Get user's vote history

### Admin - Candidates
- `POST /backend/add_candidate.php` - Create candidate
- `POST /backend/edit_candidate.php` - Update candidate
- `POST /backend/delete_candidate.php` - Archive candidate
- `POST /backend/restore_candidate.php` - Restore candidate
- `POST /backend/permanently_delete_candidate.php` - Delete permanently
- `GET /backend/get_archived_candidates.php` - Get archived candidates

### Admin - Voters
- `GET /backend/get_voters.php` - Get all voters
- `POST /backend/suspend_voter.php` - Suspend voter
- `POST /backend/unsuspend_voter.php` - Unsuspend voter
- `GET /backend/suspension_logs.php` - Get suspension logs
- `POST /backend/delete_voter.php` - Archive voter
- `POST /backend/restore_voter.php` - Restore voter
- `POST /backend/permanently_delete_voter.php` - Delete permanently
- `GET /backend/get_archived_voters.php` - Get archived voters

### Admin - Settings
- `GET /backend/get_election_settings.php` - Get election config
- `POST /backend/save_election_settings.php` - Update election config
- `POST /backend/reset_votes.php` - Clear all votes

### Contact
- `POST /backend/contact.php` - Send contact form message

---

## Design System

### Color Palette
- **Background**: `#121417` (Primary dark)
- **Cards/Panels**: `#1a1c21`, `#1d1f23` (Secondary dark)
- **Text**: `#e2e8f0`, `#f2f2f2` (Light gray)
- **Accent**: `#4e9eff` (Blue - primary CTA color)
- **Borders**: `#2d3139` (Subtle dividers)
- **Muted Text**: `#94a3b8`, `#cbd5e1` (Secondary text)

### Typography
- **Font Family**: Inter (Google Fonts)
- **Headings**: 700 weight (bold)
- **Body**: 400-500 weight (regular/medium)
- **Buttons**: 600 weight (semi-bold)

### Components
- **Cards**: Rounded corners (8-12px), subtle shadows
- **Buttons**: Blue accent with hover effects, smooth transitions
- **Inputs**: Dark backgrounds with blue focus states
- **Animations**: Fade-in, slide-in, scale effects on page load and interactions

---

## Security Features

1. **Password Security**
   - Passwords are hashed using PHP's built-in hashing functions
   - Never stored in plain text

2. **SQL Injection Prevention**
   - All queries use prepared statements
   - User inputs are sanitized and validated

3. **Session Management**
   - User sessions stored in localStorage
   - Session validation on protected pages

4. **Email Verification**
   - Welcome emails sent upon registration
   - Contact form submissions logged

5. **Access Control**
   - Admin-only endpoints require authentication
   - Voter suspension prevents login

6. **Data Validation**
   - Frontend: HTML5 validation + JavaScript checks
   - Backend: PHP validation and sanitization

---

## Email Configuration

VoteEase uses PHPMailer with Gmail SMTP. To configure:

1. **Enable 2-Step Verification** on your Gmail account
2. **Generate App Password**:
   - Go to Google Account Settings
   - Security → 2-Step Verification → App passwords
   - Create password for "Mail" on "Windows Computer"
3. **Update `backend/email.php`**:
   ```php
   $this->mail->Username = 'your-email@gmail.com';
   $this->mail->Password = 'xxxx xxxx xxxx xxxx'; // App password
   ```

### Email Features
- **Welcome Email**: Sent on registration with account confirmation
- **Contact Form**: Sends user inquiries to admin email (caidgahs@gmail.com)

---

## Browser Support

- **Chrome** 90+ ✓
- **Firefox** 88+ ✓
- **Safari** 14+ ✓
- **Edge** 90+ ✓
- **Mobile Browsers** (iOS Safari, Chrome Mobile) ✓

---

## Known Limitations

1. **Single Election**: Currently supports one active election at a time
2. **No Email Verification**: Registration doesn't require email confirmation click
3. **Basic Photo Upload**: Candidate photos use default image (upload not fully implemented)
4. **No Password Recovery**: Forgot password feature not implemented
5. **Local Storage Sessions**: Sessions don't persist across devices

---

## Future Enhancements

- [ ] Multi-election support with election switching
- [ ] Email verification with confirmation links
- [ ] Candidate photo upload functionality
- [ ] Password reset via email
- [ ] Two-factor authentication (2FA)
- [ ] Results export to PDF/CSV
- [ ] Voter registration approval workflow
- [ ] Real-time notifications with WebSockets
- [ ] Detailed analytics and reporting
- [ ] Mobile app version

---

## Troubleshooting

### Database Connection Error
- **Check**: XAMPP MySQL is running
- **Verify**: Credentials in `backend/db.php` are correct
- **Test**: Access phpMyAdmin to confirm database exists

### Emails Not Sending
- **Check**: Gmail credentials in `backend/email.php`
- **Verify**: App password is generated (not regular password)
- **Test**: Check PHP error logs in `C:\xampp\php\logs\php_error_log`

### Candidates Not Loading
- **Check**: Database has active candidates (`is_active = 1`)
- **Verify**: Election is active in `election_settings`
- **Test**: Open browser console (F12) for JavaScript errors

### Cannot Login After Registration
- **Check**: Email is unique in database
- **Verify**: Password meets requirements
- **Test**: Check voter `status` is 'active' not 'suspended'

### Votes Not Recording
- **Check**: Election dates are valid (current date between start/end)
- **Verify**: User hasn't already voted for that position
- **Test**: Check browser console and network tab for API errors

---

## Contributing

This is an educational project. If you'd like to contribute:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/YourFeature`)
3. Commit your changes (`git commit -m 'Add some feature'`)
4. Push to the branch (`git push origin feature/YourFeature`)
5. Open a Pull Request

---

## License

This project is created for educational purposes. Feel free to use and modify for learning and development.

---

## Credits

**Developer**: VoteEase Development Team  
**Contact**: caidgahs@gmail.com  
**Version**: 1.0.0  
**Last Updated**: November 29, 2025

### Technologies Used
- PHP 8.x
- MySQL/MariaDB
- Vanilla JavaScript (ES6+)
- SweetAlert2
- Tailwind CSS
- PHPMailer
- Google Fonts (Inter)

---

## Support

For questions, issues, or feature requests:
- **Email**: caidgahs@gmail.com
- **Contact Form**: Available in the application at `/public/user/contactus.html`

---

**VoteEase** - Making democracy accessible, one vote at a time.
