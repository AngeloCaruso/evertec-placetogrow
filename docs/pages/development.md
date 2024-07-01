# Development diary

---

<a name="section-1"></a>

## About the development

### Core Concepts and Decisions

This project incorporates several core concepts beyond the typical development of a Laravel application. The goal was to enhance the structure and default patterns to adhere as closely as possible to SOLID principles.

### Command Pattern with Actions

One of the key architectural decisions was to implement an interpretation of the Command Pattern through what we call Actions. These Actions complement the MVC pattern by decoupling the logic of interactions with the ORM, resulting in code that is easier to read and follow. Additionally, this approach helps keep the controllers clean and focused on handling HTTP requests.

### Use of Enums

With the introduction of Enums in recent PHP versions, managing constants has become more streamlined and clean, especially when combined with the new match statement. Throughout the project, Enums are utilized for managing permissions, roles, type fields, and other cases. This use of Enums enhances code readability and maintainability.

### Frontend Development

Initially, the project was intended to use Inertia.js as the frontend engine. However, due to time constraints, I opted to switch to Livewire, leveraging Filament components to quickly build forms and datatables. This change facilitated faster development and improved the behavior and interaction of the UI.

### Database Entity Relation

<img src="er_diagram.png" width="1200" alt="er diagram">

