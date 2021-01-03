describe('Dashboard - employee - manage time tracking', function () {
  it('should let the employee in an admin role enter his time and submit the timesheet to a manager who will validate it', function () {
    cy.loginLegacy();

    cy.createCompany();

    // create a project, task list and a task
    cy.createProject(1, 'project 1', 'PRO-1', '', 1);
    cy.createProjectTaskList(1, 1);
    cy.createProjectTask(1, 1, 1);
    cy.fillAndSubmitTimesheet(1);

    // now log in as manager and validate the timesheet
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'user', true);

    // assign Michael scott as the manager so we can validate a task has been created for the manager later on
    cy.visit('/1/employees/1');
    cy.assignManager('scott');

    // now login to the Michael Scott's account to validate the timesheet
    cy.visit('/1/account/employees/all');
    cy.get('[name=\'Michael Scott\']').invoke('attr', 'data-invitation-link').then((link) => {
      cy.acceptInvitationLinkAndGoToDashboard('admin2020', link);

      // click on the expense to see it and reject it
      cy.visit('/1/dashboard/manager');
      cy.get('[data-cy=approve-timesheet-1]').click();

      // check that the manager tab does not contain expenses anymore
      cy.visit('/1/dashboard/manager');
      cy.get('[data-cy=expense-list-blank-state]').should('exist');

      cy.logout();
    });
  });

  it('should let the employee in an admin role enter his time and submit the timesheet to a manager who will reject it', function () {
    cy.loginLegacy();

    cy.createCompany();

    // create a project, task list and a task
    cy.createProject(1, 'project 1', 'PRO-1', '', 1);
    cy.createProjectTaskList(1, 1);
    cy.createProjectTask(1, 1, 1);
    cy.fillAndSubmitTimesheet(1);

    // now log in as manager and validate the timesheet
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'user', true);

    // assign Michael scott as the manager so we can validate a task has been created for the manager later on
    cy.visit('/1/employees/1');
    cy.assignManager('scott');

    // now login to the Michael Scott's account to validate the timesheet
    cy.visit('/1/account/employees/all');
    cy.get('[name=\'Michael Scott\']').invoke('attr', 'data-invitation-link').then((link) => {
      cy.acceptInvitationLinkAndGoToDashboard('admin2020', link);

      // click on the expense to see it and reject it
      cy.visit('/1/dashboard/manager');
      cy.get('[data-cy=reject-timesheet-1]').click();

      // check that the manager tab does not contain expenses anymore
      cy.visit('/1/dashboard/manager');
      cy.get('[data-cy=expense-list-blank-state]').should('exist');

      cy.logout();
    });
  });

  it('should let the employee in an HR role enter his time and submit the timesheet to a manager who will validate it', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.get('body').invoke('attr', 'data-account-id').then(function (userId) {
      cy.changePermission(userId, 200);
    });

    // create a project, task list and a task
    cy.createProject(1, 'project 1', 'PRO-1', '', 1);
    cy.createProjectTaskList(1, 1);
    cy.createProjectTask(1, 1, 1);
    cy.fillAndSubmitTimesheet(1);
    cy.hasEmployeeLog('Submitted the timesheet of the week started', '/1/employees/1');

    // now log in as manager and validate the timesheet
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'user', true);

    // assign Michael scott as the manager so we can validate a task has been created for the manager later on
    cy.visit('/1/employees/1');
    cy.assignManager('scott');

    // now login to the Michael Scott's account to validate the timesheet
    cy.visit('/1/account/employees/all');
    cy.get('[name=\'Michael Scott\']').invoke('attr', 'data-invitation-link').then((link) => {
      cy.acceptInvitationLinkAndGoToDashboard('admin2020', link);

      // click on the expense to see it and reject it
      cy.visit('/1/dashboard/manager');
      cy.get('[data-cy=approve-timesheet-1]').click();

      // check that the manager tab does not contain expenses anymore
      cy.visit('/1/dashboard/manager');
      cy.get('[data-cy=expense-list-blank-state]').should('exist');

      cy.logout();
    });
  });

  it('should let the employee in an HR role enter his time and submit the timesheet to a manager who will reject it', function () {
    cy.loginLegacy();

    cy.createCompany();

    // now log in as manager and validate the timesheet
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'user', true);

    // assign Michael scott as the manager so we can validate a task has been created for the manager later on
    cy.visit('/1/employees/1');
    cy.assignManager('scott');

    cy.get('body').invoke('attr', 'data-account-id').then(function (userId) {
      cy.changePermission(userId, 200);
    });

    // create a project, task list and a task
    cy.createProject(1, 'project 1', 'PRO-1', '', 1);
    cy.createProjectTaskList(1, 1);
    cy.createProjectTask(1, 1, 1);
    cy.fillAndSubmitTimesheet(1);
    cy.hasEmployeeLog('Submitted the timesheet of the week started', '/1/employees/1');

    // now login to the Michael Scott's account to validate the timesheet
    cy.visit('/1/account/employees/all');
    cy.get('[name=\'Michael Scott\']').invoke('attr', 'data-invitation-link').then((link) => {
      cy.acceptInvitationLinkAndGoToDashboard('admin2020', link);

      // click on the expense to see it and reject it
      cy.visit('/1/dashboard/manager');
      cy.get('[data-cy=reject-timesheet-1]').click();

      // check that the manager tab does not contain expenses anymore
      cy.visit('/1/dashboard/manager');
      cy.get('[data-cy=expense-list-blank-state]').should('exist');

      cy.logout();
    });
  });

  it('should let a normal employee enter his time and submit the timesheet to a manager who will validate it', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.get('body').invoke('attr', 'data-account-id').then(function (userId) {
      cy.changePermission(userId, 300);
    });

    // create a project, task list and a task
    cy.createProject(1, 'project 1', 'PRO-1', '', 1);
    cy.createProjectTaskList(1, 1);
    cy.createProjectTask(1, 1, 1);
    cy.fillAndSubmitTimesheet(1);

    // now log in as manager and validate the timesheet
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'user', true);

    // assign Michael scott as the manager so we can validate a task has been created for the manager later on
    cy.visit('/1/employees/1');
    cy.assignManager('scott');

    // now login to the Michael Scott's account to validate the timesheet
    cy.visit('/1/account/employees/all');
    cy.get('[name=\'Michael Scott\']').invoke('attr', 'data-invitation-link').then((link) => {
      cy.acceptInvitationLinkAndGoToDashboard('admin2020', link);

      // click on the expense to see it and reject it
      cy.visit('/1/dashboard/manager');
      cy.get('[data-cy=approve-timesheet-1]').click();

      // check that the manager tab does not contain expenses anymore
      cy.visit('/1/dashboard/manager');
      cy.get('[data-cy=expense-list-blank-state]').should('exist');

      cy.logout();
    });
  });

  it('should let a normal employee enter his time and submit the timesheet to a manager who will reject it', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.get('body').invoke('attr', 'data-account-id').then(function (userId) {
      cy.changePermission(userId, 300);
    });

    // create a project, task list and a task
    cy.createProject(1, 'project 1', 'PRO-1', '', 1);
    cy.createProjectTaskList(1, 1);
    cy.createProjectTask(1, 1, 1);
    cy.fillAndSubmitTimesheet(1);

    // now log in as manager and validate the timesheet
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'user', true);

    // assign Michael scott as the manager so we can validate a task has been created for the manager later on
    cy.visit('/1/employees/1');
    cy.assignManager('scott');

    // now login to the Michael Scott's account to validate the timesheet
    cy.visit('/1/account/employees/all');
    cy.get('[name=\'Michael Scott\']').invoke('attr', 'data-invitation-link').then((link) => {
      cy.acceptInvitationLinkAndGoToDashboard('admin2020', link);

      // click on the expense to see it and reject it
      cy.visit('/1/dashboard/manager');
      cy.get('[data-cy=reject-timesheet-1]').click();

      // check that the manager tab does not contain expenses anymore
      cy.visit('/1/dashboard/manager');
      cy.get('[data-cy=expense-list-blank-state]').should('exist');

      cy.logout();
    });
  });
});
