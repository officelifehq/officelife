# Introduction

## What is OfficeLife?

OfficeLife is a different kind of human resource software. Unlike other soulless HR tools, it has been designed to mimic and represent what happens in the real world. Its main goals are to:

- help managers be more empathetic,
- help companies be better at managing people's dreams and ambitions,
- help employees better communicate with their employers.

OfficeLife aims to be a combination of softwares like BambooHR, Officevibe and Monica – although in a single application and at a much more reasonable price.

At a high level, OfficeLife allows a company to:

*
*
*
*
*

It also gives users and companies a complete control over their data. OfficeLife is open source and can be installed on your own server if you so desire.

To use the software, companies have to pay a fair fixed annual fee, regardless of the size of your team. I do not sell your data, don't use ads nor use external analytical services. You can export your data at anytime or use the API without restrictions.

In terms of user experience, the software aims to be simple to use with the minimum amount of configuration. The design itself is not a priority - user experience is.

Technically, the software is developed with boring, proven, predictible, easy to maintain technologies that make the tool fast and secure. I want to create a product useful for users and companies, not something that is technologically exciting.

## Why is OfficeLife different?

## Pricing and open source

##

# Development

# Hosting OfficeLife on your own server

# OfficeLife in 10 minutes

OfficeLife is built around the notion that while companies own data about their employees, users have complete control over which data they give to companies.

## Setup

### Tips for developers

* Dates must all have the `datetime` data type so we can use SQLite for testing purposes, even when we want to use a `date` type. Make sure to fill the date object with a trailing `00:00:00`. Otherwise we get conflicts between mySQL and SQlite.
* Migrations should not have a `down` method.
* Do not use Resources when you can avoid doing so. A resource should never contain nested resources if it’s used by a web controller as it can lead to a lot of unecessary queries. Resources should always be used in the API.
* When you need to build some data to pass to the web view, build your own collection instead, as the controllers should optimize the queries made to the database as much as possible. If you need a specific collection that is used in many places, consider creating a Collection in the `app/Http/Collections` folder. This acts like a Resource but for pure web queries. Those Collections use the `toObject` method in each model. Also, never put nested collections in a Collection – this will only create more unnecessary queries to the database.

#### Crons

* `LogMissedWorklogEntry` that runs at 11pm every day. This job checks all the employees who hasn't logged a worklog on the current day, and increments the `consecutive_worklog_missed` field in the Employee table for each found employee.

#### Shared data in Vue

Data is shared accross views in Vue within Inertia. Shared data come from AppServiceProvider.

### Users vs employees

OfficeLife makes the distinction between users and employees.

A user is someone who creates an account on the OfficeLife platform. An employee is an entity who is part of a company on officelife. A user can be an employee in one or more companies, but an employee can only be linked to a single user. A user account is necessary for each employee to interact with the software and change data. HoIver you don't need to give accounts to employees if you don't want to.

Once a user has an account on OfficeLife, he can decide to either create a company, or join an existing company.

The natural way to create this software would have been that an employee is a user, and get rid of those two notions altogether to only keep the notion of User. This would have been a severe limitation though. In an ideal world, every company would run on OfficeLife to manage their human resources, and once a user has a profile on OfficeLife, he can simply join other companies as he changes jobs, and most data about him would follow (if he wants to). It's important to realize that at any time, the user has the control over his data and can decide to remove them from the company he's joined.

At the database level, the <code>Users</code> table contains almost the same data as the <code>Employees</code> table. That is because information in the Users table is information given by the user himself, whereas information in the Employees table is filled by the company.

### Creating an account for a company

To create an account, you simply need a valid email address and a strong password. Nothing else is needed, really. Upon signing up, the system will send you an email to confirm the validity of the email, but you can use the software without it. Confirming the email address is necessary to be able to add employees to your account.

Once you create your account, you are presented with the option to either create a company, or join an existing company.

Clicking on Create a company will ask you to name the company, and that's it. There is one important rule here: the company name is unique on an instance of officelife. That means it's not possible to create two companies named Microsoft, for instance. If you believe that the name of your company has been already used on the system and that this is an abuse, please contact us - I will sort this out.

As you are the one who has created the company, you will be an administrator of the company, with full control over the account of the company. To know more about what it means, read the documentation about roles.

### Creating an account to join an existing company

### Understanding roles

When adding an employee in the system, you need to indicate a role. A role is a set of rights the employee will have in the system, once the employee is linked to a user account.

There are only three roles in OfficeLife:
- Administrator
- Human Resource Representative
- Employee.

I want to keep the application simple, so I want as few roles as possible. With those permissions and the presence of the audit log that tracks everything that happens in the application, those roles should be enough.

That being said, an employee can be a manager. When an employee is a manager, he has some controls and additional poIrs over a regular employee, but only upon the employees he manages.

#### Audit logs

All actions made in the software are logged, along with the identity of who has done what and when. An action is always recorded at the company level, and listed in the Audit log page in the Settings panel. This page is available only to administrators of the company.

Actions that are made on an employee are also logged at the employee level, and visible on the employee page by clicking on the View Log button. Those logs only are visible by the employee himself, or by employees with the HR or administrator role.

Finally, actions are that made on a team are, again, logged at the team level.

### Adding employees

### Inviting users

### Teams

A team is a group of people. This is vague on purpose. It’s up to you to decide whether a team is a department, a separate business entity or a team within another bigger team.

A team is created in Adminland by an administrator or a human resource representative. A team has one team leader (and only one). A team can have one description that anyone who is part of the company can set (remember, everything in the system is logged, so don't fear that someone does something that you won't be able to track. Also, trust).

#### Useful links

A team has a life on its own. There are many online places that are useful to a particular team: a dedicated Slack channel, a mailing list name, a private joke... Because each team is unique, it deserves its own set of links.

A team can have multiple links, called useful links. Anyone can add, edit or remove those links. Use this feature to help your team, or guide users who visit your Team page.

#### Team news

Every team can have a dedicated set of news, called Team News. These news only appear on the team's page. Anyone in the company can create and manage a news for a team – it’s up to the company to make sure everyone is smart enough not to pollute other teams.

The audit log for the team news are only available to the users with the administrator role.

### Work logs

Employees can log the work they've done for a given day. When an employee provides this information, it can be seen by anyone in the company for the current week. Once the week has passed, a complete log can be consulted right on the employee profile, but only for those people:
- the employee himself,
- people with at least the HR role.

Employees can only log what they've done once per day. By definition, I don't allow someone to change the date of a post they have written – that means if they omit one day, they can't come back the next day and fill the information. This is done on purpose in order to force people to actually use this feature if a team needs it.

Every night at 11pm (UTC), the server checks whose employees have logged their work and keeps count. It’s up to the company to decide how they want to enforce this policy of logging the work. A company can choose to create automated rules using the powerful flow feature if they want to be warned when an employee skips, say, 7 days of logging work in a row.

### Employee's description

Employees can add a description to their profile, allowing them to personalize a bit their profile page. The description supports Markdown. Anyone in the company will be able to see this description.

### Employee's address

Employees can set their home address on their profile page. HR representatives and administrators also have the possibility to set an address on behalf of the employee. Once an address is set, OfficeLife will automatically pull a map to visualize this address on the employee's profile page.

A note on privacy: an employee's complete address is only shown to the employee himself, or to employees with the HR or administrator roles. When a regular employee goes to see the profile of another employee, he will only see a partial address, aka the city and the country. The map that is displayed, only shows the city as well, on purpose.

Employees can change addresses as much as they want, there is no limitation.

### Gender identity

OfficeLife respects everyone's gender and identity. This is why employees can choose to be identified how they want to, by choosing from a long list of available gender neutral terms.

### Morale

Employees can indicate how they feel each day. They have the choice between a good day, a normal day or a bad day. After they have indicated their feelings, they can also add a comment to it. This feeling can't be changed once it’s logged, on purpose.

This information is used to take the pulse on how a team or an entire company is going, and therefore will be displayed on a team page and on the company profile's page. HoIver, data is completely anonymized. It will be impossible for anyone regardless of their roles, to know who has voted and what is the result of this vote.

To be perfectly clear, the feeling, along with its comment, will be shown:
- on the employee's profile page. In this case, only the employee will see this information. No one, not even an administrator, will be able to see how the employee feels.
- on the team's page. In this case, I will only show the feeling of every team member without indicating who has said what, as Ill as the average feeling of the team on a given time range.
- on the company's profile. In this case, like the team's page, I will only show the average feeling of the company.

### Paid time offs (PTO)

Paid Time Off is the term used in the HR industry to represent the bank of days that the employee gains each month by working for his/her employer. There are entire companies built around PTOs management, and they are associated most of the time with payroll information. To be clear, I won't do payroll in this software. It’s a complex field, with a lot of regulations, and as a small independant project, I don't have the resources to work in this domain.

Softwares that deal with PTOs are boring, complex and full of features. They try to cover all the possible use cases. They also promote gates, and permissions, and reflect the bad side of reality. I refuse to be part of this. I want to create a software that promotes how I envision companies should deal with employees and their rights. My approach is mainly based on how 37Signals has built Basecamp: focus on what's truly essential and forget the rest.

This is my take on how we should handle PTOs, and this is what OfficeLife allows employees to do:
- An employee wants to take a break from work.
- She checks her balance. If she has enough holidays in her bank, she logs a holiday.
- Her manager and her team are informed that she wants to take a break.
- That's it. No rejecting/approving processes. Going on holidays shouldn't be granted by an employer - it’s a legal right.

Time offs are based on a yearly calendar. Each company has its own set of defined holidays during a given year. When you create an account on OfficeLife, your account is populated with 5 calendars for each of the next 5 years. On these calendars, weekends are considered time offs by default, and it’s up to you to define which other days you consider as time offs/holidays depending on the country you are living in.

In the Adminland section of your account, you can manage your own PTO policy and change the following settings:
* how many days are considered working days each year. This is used to calculate how many holidays employees gain each day by working for the company, based on the number of holidays the employer gives each year. This will be also used to know which days should be taken into account when an employee wants to take a day off. For each year, you can edit the calendar and click on a day to mark it off. It’s up to your company to decide which days are considered off, and as it depends on your country, only weekends are considered off by default. Also, it’s not possible to change the status of weekends. They will always be considered offs for everyone.
* the default number of allowed sick days and the default number of Personal Time Off days. Every new employee that you create in your system will have those default values applied to her profile. However, changing those two settings will not be applied to existing employees.

#### Log a time off

Employees can log a time off in the future, or in the past. They can only choose between a half and a full day. When they log a time off, they have to choose between a holiday, a sick day or a PTO day. There can only be two half days for any given day. Also, of course, it’s not possible to log a time off on a day the company indicated that it was a holiday for the entire company.

Note that an employee with the HR or admin privileges can also log a time off for any employee in the system.

#### Earning time off

Every day employees gain a tiny amount of new holidays, that adds up in their balance. This is calculated based on a simple formula: the system knows how many working days there are in the year as defined in the company's PTO policy, and the system also knows how many numbers of PTOs the employee has. Therefore it’s easy to know how much time off an employee earns each working day. A cron runs every night to calculate employee's new balance.

#### Note on unlimited vacation policies

I personally believe that unlimited vacation policies are bullshit. When they exist, people actually don't take holidays at all. This is why, while this concept is supported in OfficeLife, managers will be warned when people haven't taken an holidays for a few months, to make sure people don't burn out.