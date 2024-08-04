# Development diary

---

## About the development

### Core Concepts and Decisions

This project incorporates several core concepts beyond the typical development of a Laravel application. The goal was to enhance the structure and default patterns to adhere as closely as possible to SOLID principles.

### Stage 1

---

### Command Pattern with Actions

One of the key architectural decisions was to implement an interpretation of the Command Pattern through what we call Actions. These Actions complement the MVC pattern by decoupling the logic of interactions with the ORM, resulting in code that is easier to read and follow. Additionally, this approach helps keep the controllers clean and focused on handling HTTP requests.

### Use of Enums

With the introduction of Enums in recent PHP versions, managing constants has become more streamlined and clean, especially when combined with the new match statement. Throughout the project, Enums are utilized for managing permissions, roles, type fields, and other cases. This use of Enums enhances code readability and maintainability.

### Frontend Development

Initially, the project was intended to use Inertia.js as the frontend engine. However, due to time constraints, I opted to switch to Livewire, leveraging Filament components to quickly build forms and datatables. This change facilitated faster development and improved the behavior and interaction of the UI.

### Database Entity Relation v1

<img src="er_diagram.png" width="1200" alt="er diagram">

### Stage 2

---

### Frontend Development: Livewire or Inertia.js?

With the introduction of new features in Stage 2, the decision on frontend technology was expanded. Instead of choosing between Livewire and Inertia.js, why not use both?

Stage 2 includes the implementation of a public interface where users, without needing to register, can view available microsites and make payments using the Placetopay gateway. This new interface was developed using Vue.js, paired with Inertia.js, to leverage the power of server-side rendering (SSR).

### Strategy Pattern

As mentioned in the previous section, this stage introduces the implementation of the Placetopay payment gateway. Given the nature of the project, it is possible that more payment gateways may need to be integrated in the future, offering users more payment options. This is where the Strategy pattern comes into play. The Strategy pattern abstracts the logic of various use cases that share common features, similar to the concept of inheritance.

*Disclaimer: The process described below is intended for payment gateways that implement WebCheckout as the payment process.*

In this scenario, a payment gateway can be of type Placetopay, PayPal, Stripe, etc. Generally, all these gateways must go through a process of authentication, data submission, and payment link generation.

From a technical perspective, the controller, which receives the request, acts as the context. To maintain the Actions pattern from Stage 1, the controller calls an Action, which is responsible for invoking the required Strategy. Once resolved, the Action calls the necessary functions to carry out the process without needing to know or understand the specific Strategy being used.

An important aspect of the Strategy pattern is resolving the class to be called for the Strategy. Laravel offers the option to use its service container to handle this process. The service container allows Laravel to automatically resolve the class in a magical and abstract way, given a parameter. However, I chose to continue using Enums to resolve this, as it offers better code readability and traceability in this particular case. In the Enums directory, there's a folder called Gateways with a file named `GatewayTypes`, where the class to be returned for each case is specifically defined. Additionally, in the Payments model, the `gateway` attribute is cast, making it easy to see what is happening with that field and trace the logic through the files.

<img src="strategy.png" width="500" alt="er diagram">

For more details on this pattern, you can read about it at [Refactoring GURU Strategy Pattern](https://refactoring.guru/es/design-patterns/strategy/php/example#example-1).

### Builder Pattern

Complementing the integration of the Strategy pattern mentioned in the previous section, each specific Strategy implements an interface that defines the methods each integration must follow. For the implementation of the Placetopay gateway, the Builder pattern was chosen. This pattern allows each Strategy the flexibility to carry out its process as needed. The general flow involves authentication, data construction, and obtaining a payment link. This way, each integration can build its flow and maintain generalized logic without affecting other integrations.

For more details on this pattern, you can read about it at [Refactoring GURU Builder Pattern](https://refactoring.guru/es/design-patterns/builder/php/example#example-1).

### Polymorphism

Another new feature introduced in Stage 2 is the ability to define ACLs (Access Control Lists) for our users. An admin user with permissions to access the module can define which entity (Microsite or User) a specified user can access.

To implement this feature, we utilized the concept of polymorphism provided by Laravel's Eloquent. The ACLs table has two fields that define the model and its respective ID, referred to as *Controllable*. However, not all models can be *Controllable*, so there is an Enum called `ControllableTypes` that defines which models can be used to set our ACLs.

### Queues

Due to the nature of integrations involving payments, it is necessary to implement a system that can handle processes in the background while the respective entities manage the transaction.

Queues allow us to separate these processes so they do not interfere with or block the workflow. In this case, a job is queued every time a user completes a payment via WebCheckout and reaches the payment summary view. This job includes a validation step to ensure it only gets queued if the payment status is "Pending."

### Database Entity Relation v2

<img src="er_diagram_v2.png" width="1200" alt="er diagram">

