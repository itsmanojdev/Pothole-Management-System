# Pothole Management System

Tech Stack: Laravel, Blade, MySQL, JavaScript, Google Maps API, Tailwind CSS
Role: Full-Stack Developer
Duration: April 2025 - May 2025 

Live Demo: <a href="https://pothole-management-system.onrender.com">Pothole Management System</a>

### Credentials
***Super Admin***  
Email: mksuperadmin@gmail.com  
Password: Superadmin@123  

***Admin***  
Email: mkadmin@gmail.com  
Password: Admin@123  

***Citizen***  
Email: mkcitizen@gmail.com  
Password: Citizen@123  

## Project Overview
Developed a full-featured web application to report, manage, and resolve potholes in urban areas by different user roles: Citizen, Admin, and Super Admin.

## System Design
### Pothole List

**<ins>Super Admin:</ins>** Can view all the potholes reported  
**<ins>Admin:</ins>** Can view only potholes assigned to them and all open potholes  
**<ins>Citizen:</ins>** Can view only potholes reported by them    

_All users can filter by pothole name, location & pothole status_

### Pothole Status
- Pothole Has Status: Open, Assigned, In Progress, Resolved, Verified.  
- Citizen reports the pothole, and it will have the default status 'Open'.
- Admin / Super Admin can take up potholes, which moves the pothole to the 'Assigned' status
- Admin / Super Admin can update status to 'In Progress', 'Resolved'. Citizens cannot update the pothole status
- Potholes which are in 'Resolved' status can be verified by Super Admins / Citizens.
- Citizens have option to delete pothole while they are in 'Open' or 'Assigned' state. Once the repair started, they could no longer delete the pothole. 

# Key Features & Responsibilities

🔐 Multi-role Authentication: Implemented role-based access by leveraging Laravel middleware (Citizen, Admin, Super Admin).

🗺️ Google Maps Integration: Enabled real-time location selection and map viewing for pothole reports.

📸 File Uploads: Integrated image uploads with validation and dynamic image preview.

📝 Form Validation: Built robust Laravel Form Requests with custom error messages and partial update handling using sometimes rules.

🧠 Dynamic Dashboards: Created role-specific dashboards showing relevant data.

🔄 Toastr Notifications & Audio Alerts: Added interactive feedback on events using toastr.js and custom audio.

📥 Database Seeding & Factories: Generated realistic test data using Laravel factories with dynamic image and map coordinates.

📦 RESTful Routes & Controllers: Followed Laravel resource controller structure with named routes for CRUD operations.

🔍 Search & Filter: Implemented real-time filters for status and keyword search with pagination. Have role-based filters - admin can view only open potholes and potholes assigned to them. The user can view only the potholes that they created

🧱 Reusable Blade Components: Built input fields and buttons as Blade components to maintain consistency and reduce code duplication.

🎨 Interactive UI: Styled using Tailwind CSS for consistent layout.

🔧 Deployment-Ready: Managed environment configs (e.g., Google Maps key via .env) and made the project ready for production.

