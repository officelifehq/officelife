# Introduction

## Homas in 1 minute

Homas is a new kind of human resource software. Unlike other robotic HR tools, it has been designed to mimic what happens in the real world. Its main goals are to:
- help managers be more empathetic,
- help companies be better at managing people's dreams and ambitions,
- help employees communicate with their employers,
- give users and companies a complete control over their data,
- reduce the use of LinkedIn and all the bullshit associated with it in today's world.

Homas is open source and can be installed on a server that you own if you so desire.

In terms of user experience, the software aims to be simple to use with the minimum amount of configuration. Design is not a priority - user experience is.

To use the software, users have to pay a fair fixed monthly fee, regardless of the size of your team. We do not sell your data, don't use ads nor use external analytical services. You can export your data at anytime or use the API without restrictions.

Technically, the software is developed with boring, proven, predictible, easy to maintain technologies that make the tool fast and secure. We want to create a product useful for our users, not something that is technologically exciting.

## Homas in 10 minutes

Homas is built around the notion that while companies own data about their employees, users have complete control over which data they give to companies.

## Setup

### Users vs employees

Homas makes the distinction between users and employees.

A user is someone who creates an account on the Homas platform. An employee is an entity who is part of a company on Homas. A user can be an employee in one or more companies, but an employee can only be linked to a single user. A user account is necessary for each employee to interact with the software and change data. However you don't need to give accounts to employees if you don't want to.

Once a user has an account on Homas, he can decide to either create a company, or join an existing company.

The natural way to create this software would have been that an employee is a user, and get rid of those two notions altogether to only keep the notion of User. This would have been a severe limitation though. In an ideal world, every company would run on Homas to manage their human resources, and once a user has a profile on Homas, he can simply join other companies as he changes jobs, and most data about him would follow (if he wants to). It's important to realize that at any time, the user has the control over his data and can decide to remove them from the company he's joined.

### Creating an account for a company

To create an account, you simply need a valid email address and a strong password. Nothing else is needed, really. Upon signing up, the system will send you an email to confirm the validity of the email, but you can use the software without it. Confirming the email address is necessary to be able to add employees to your account.

Once you create your account, you are presented with the option to either create a company, or join an existing company.

Clicking on Create a company will ask you to name the company, and that's it. There is one important rule here: the company name is unique on an instance of Homas. That means it's not possible to create two companies named Microsoft, for instance. If you believe that the name of your company has been already used on the system and that this is an abuse, please contact us - we will sort this out.

As you are the one who has created the company, you will be an administrator of the company, with full control over the account of the company. To know more about what it means, read the documentation about roles.

### Creating an account to join an existing company

### Understanding roles

When adding an employee in the system, you need to indicate a role. A role is a set of rights the employee will have in the system, once the employee is linked to a user account.

There are only three roles in Homas:
- Administrator
- Human Resource Representative
- Employee.

We want to keep the application simple, so we want as few roles as possible. With those permissions and the presence of the audit log that tracks everything that happens in the application, those roles should be enough.

That being said, an employee can be a manager. When an employee is a manager, he has some controls and additional powers over a regular employee, but only towards the employees he manages.

### Adding employees

### Inviting users

### Teams

A team is a group of people. This is vague on purpose. It’s up to you to decide whether a team is a department, a separate business entity or a team within another bigger team.

A team is created in Adminland by an administrator or a human resource representative. A team has one team leader (and only one).