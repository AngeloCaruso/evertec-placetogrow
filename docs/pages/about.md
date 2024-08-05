# About

---

## Description

This project has been developed as part of the Evertec PHP Bootcamp 2024, aimed at showcasing advanced PHP and Laravel skills. The application is structured into three main modules, each designed to address specific functionalities and user needs. The core focus of this development is the microsites administration module, which provides robust and flexible tools for managing multiple microsites efficiently.

Upon accessing the application, users will be greeted with an intuitive interface that simplifies navigation and enhances the user experience. The main modules include:

1. **User Management Module**: This module allows administrators to create, update, and delete user accounts. It also includes features for assigning roles and permissions, ensuring that each user has appropriate access levels.

2. **Role Management Module**: This module is designed to manage user roles and permissions within the application. Administrators can define new roles, modify existing ones, and assign specific permissions to each role. This ensures a granular level of control over what each user can access and perform within the application.

3. **Microsites Administration Module**: The centerpiece of this application, this module provides comprehensive tools for managing microsites. Users can create new microsites, customize their appearance, and configure settings to meet specific needs. This module also includes analytics and reporting features to track the performance and engagement of each microsite.

4. **ACL Management (New 🔥)**: This module allows a user with the appropriate permissions to manage ACLs, assigning specific users the ability to access certain resources.

5. **Public Microsites (New 🔥)**: A new public view is now available for users without needing to log in. This view allows users to make payments using the Placetopay WebCheckout gateway. You can visit it in `/microsites`

## Changelog

All notable changes related to the project. Click on the release title to view its respective development notes.

## [Release Stage 2](/pages/development?id=stage-2) - 2024-08-04
### Added
- **Public Microsite Page**: A public page for users to view available microsites and make payments without logging in.
- **Placetopay WebCheckout Integration**: Integration with Placetopay for handling payments.
- **ACLs CRUD Module (Admin)**: A module for managing Access Control Lists (ACLs) in the admin panel.
- **My Payments Module (Admin)**: A module for administrators to view and manage payments.

### Removed
- Users no longer have a `microsite_id` assigned. This is now managed via ACLs.

---

## [Release Stage 1](/pages/development?id=stage-1) - 2024-06-30
### Added
- **User Management CRUD**: A module for managing user accounts.
- **Microsite Management CRUD**: A module for managing microsites.
- **Roles and Permissions CRUD**: A module for managing user roles and permissions.
- **Docker Compose Integration for Local Development**: Set up Docker Compose for local development environments.
- **CI for GitHub Actions**: Continuous Integration setup using GitHub Actions.

