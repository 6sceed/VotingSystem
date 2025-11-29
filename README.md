# VoteEase - Online Voting System

A secure web-based voting platform built with PHP, MySQL, and vanilla JavaScript. Features real-time results, user management, and comprehensive admin controls.

## Features

**For Voters:**

- Secure registration & login with email notifications
- Real-time voting and live results dashboard
- Profile management and vote tracking
- Responsive design with smooth animations

**For Admins:**

- Election configuration (title, dates, status)
- Candidate management (add, edit, archive, restore)
- Voter management (suspend, archive, view logs)
- Live results monitoring and vote reset

## Tech Stack

- **Frontend:** HTML5, CSS3, Vanilla JavaScript, SweetAlert2, Tailwind CSS
- **Backend:** PHP 8.x, MySQL/MariaDB, PHPMailer
- **Security:** Password hashing, prepared statements, input validation

## Installation

1. **Setup Database**

   ```bash
   # Place project in C:\xampp\htdocs\voting_system
   # Open phpMyAdmin: http://localhost/phpmyadmin
   # Create database: voting_system
   # Import: asset/database/voting_system.sql
   ```

2. **Configure Database** (`backend/db.php`)

   ```php
   $host = "localhost";
   $user = "root";
   $password = "";
   $database = "voting_system";
   ```

3. **Configure Email** (`backend/email.php`) - Optional

   ```php
   $this->mail->Username = 'your-email@gmail.com';
   $this->mail->Password = 'your-app-password'; // Gmail App Password
   ```

4. **Start Server**
   - Open XAMPP, start Apache & MySQL
   - Access: `http://localhost/voting_system/`

## Database Schema

**voters:** id, name, email, password, address, phone, age, status, created_at  
**candidates:** id, name, position, photo, bio, is_active, created_at  
**votes:** id, user_id, candidate_id, position, voted_at  
**election_settings:** id, election_title, description, start_date, end_date, is_active  
**suspension_logs:** id, voter_id, reason, suspended_by, suspended_at

## Key Endpoints

**Auth:** `/backend/register.php`, `/backend/login.php`, `/backend/profile.php`  
**Voting:** `/backend/get_candidates.php`, `/backend/cast_vote.php`, `/backend/get_live_results.php`  
**Admin:** `/backend/add_candidate.php`, `/backend/suspend_voter.php`, `/backend/save_election_settings.php`  
**Contact:** `/backend/contact.php`

## Usage

**Voters:**

1. Register account (Name format: Last, First M)
2. Login and navigate to dashboard
3. Click "Vote Now", select candidates, submit
4. View live results on dashboard

**Admins:**

1. Access `admindashboard.html`
2. Configure election in Settings tab
3. Manage candidates and voters
4. Monitor results in real-time

## Troubleshooting

- **DB Connection Error:** Check XAMPP MySQL is running, verify `backend/db.php`
- **Emails Not Sending:** Verify Gmail App Password in `backend/email.php`
- **Candidates Not Loading:** Check `is_active = 1` in database, election is active
- **Cannot Login:** Verify voter status is 'active', not 'suspended'

## Credits

**Contact:** 6sceed@gmail.com  
**Version:** 1.0.0  
**Last Updated:** November 29, 2025

## Images

<img width="1920" height="965" alt="Image" src="https://github.com/user-attachments/assets/aac40fc3-8f5f-4a94-ae10-5033b04da9a4" /> <img width="1894" height="956" alt="Image" src="https://github.com/user-attachments/assets/2d568993-730b-4982-b379-c0a0e3b2b839" />
<img width="1867" height="958" alt="Image" src="https://github.com/user-attachments/assets/8af0059e-f5ef-4c82-9a2b-e6b3057dd678" /> <img width="1880" height="949" alt="Image" src="https://github.com/user-attachments/assets/5cf44253-cf63-4580-bdb7-21f35a7adb09" />
