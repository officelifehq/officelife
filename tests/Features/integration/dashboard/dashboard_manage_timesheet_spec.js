describe('Dashboard - employee - manage time tracking', function () {
  it('should let the employee in an admin role enter his time', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.get('body').invoke('attr', 'data-account-id').then(function (userId) {
      cy.changePermission(userId, 100);
    });

    // create a project, task list and a task
    cy.createProject(1, 'project 1', 'PRO-1', '', 1);
    cy.createProjectTaskList(1, 1);
    cy.createProjectTask(1, 1, 1);

    cy.visit('/1/dashboard/timesheet');

    // add a new row
    cy.get('[data-cy=timesheet-add-new-row]').click();
    cy.get('[data-cy=project-selector]').click();
    cy.get('ul.vs__dropdown-menu>li').eq(0).click();

    cy.get('[data-cy=task-selector]').click();
    cy.get('ul.vs__dropdown-menu>li').eq(0).click();
    cy.get('[data-cy=submit-timesheet-new-row]').click();

    // fill the newly created row
    cy.get('[data-cy=timesheet-1-day-0-hours]').type('1');
    cy.get('[data-cy=timesheet-1-day-0-minutes]').type('30');
    cy.get('[data-cy=timesheet-1-day-1-hours]').type('1');
    cy.get('[data-cy=timesheet-1-day-1-minutes]').type('30');
    cy.get('[data-cy=timesheet-1-day-4-hours]').type('1');
    cy.get('[data-cy=timesheet-1-day-4-minutes]').type('30');

    // submit timesheets
    cy.get('[data-cy=timesheet-submit-timesheet]').click();
    cy.get('[data-cy=timesheet-status-awaiting]').should('exist');

    cy.hasAuditLog('Submitted the timesheet of the week started', '/1/employees/1');
    cy.hasEmployeeLog('Submitted the timesheet of the week started', '/1/employees/1');
  });

  it('should let the employee in an HR role enter his time', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.get('body').invoke('attr', 'data-account-id').then(function (userId) {
      cy.changePermission(userId, 200);
    });

    // create a project, task list and a task
    cy.createProject(1, 'project 1', 'PRO-1', '', 1);
    cy.createProjectTaskList(1, 1);
    cy.createProjectTask(1, 1, 1);

    cy.visit('/1/dashboard/timesheet');

    // add a new row
    cy.get('[data-cy=timesheet-add-new-row]').click();
    cy.get('[data-cy=project-selector]').click();
    cy.get('ul.vs__dropdown-menu>li').eq(0).click();

    cy.get('[data-cy=task-selector]').click();
    cy.get('ul.vs__dropdown-menu>li').eq(0).click();
    cy.get('[data-cy=submit-timesheet-new-row]').click();

    // fill the newly created row
    cy.get('[data-cy=timesheet-1-day-0-hours]').type('1');
    cy.get('[data-cy=timesheet-1-day-0-minutes]').type('30');
    cy.get('[data-cy=timesheet-1-day-1-hours]').type('1');
    cy.get('[data-cy=timesheet-1-day-1-minutes]').type('30');
    cy.get('[data-cy=timesheet-1-day-4-hours]').type('1');
    cy.get('[data-cy=timesheet-1-day-4-minutes]').type('30');

    // submit timesheets
    cy.get('[data-cy=timesheet-submit-timesheet]').click();
    cy.get('[data-cy=timesheet-status-awaiting]').should('exist');

    cy.hasEmployeeLog('Submitted the timesheet of the week started', '/1/employees/1');
  });

  it('should let a normal employee enter his time', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.get('body').invoke('attr', 'data-account-id').then(function (userId) {
      cy.changePermission(userId, 300);
    });

    // create a project, task list and a task
    cy.createProject(1, 'project 1', 'PRO-1', '', 1);
    cy.createProjectTaskList(1, 1);
    cy.createProjectTask(1, 1, 1);

    cy.visit('/1/dashboard/timesheet');

    // add a new row
    cy.get('[data-cy=timesheet-add-new-row]').click();
    cy.get('[data-cy=project-selector]').click();
    cy.get('ul.vs__dropdown-menu>li').eq(0).click();

    cy.get('[data-cy=task-selector]').click();
    cy.get('ul.vs__dropdown-menu>li').eq(0).click();
    cy.get('[data-cy=submit-timesheet-new-row]').click();

    // fill the newly created row
    cy.get('[data-cy=timesheet-1-day-0-hours]').type('1');
    cy.get('[data-cy=timesheet-1-day-0-minutes]').type('30');
    cy.get('[data-cy=timesheet-1-day-1-hours]').type('1');
    cy.get('[data-cy=timesheet-1-day-1-minutes]').type('30');
    cy.get('[data-cy=timesheet-1-day-4-hours]').type('1');
    cy.get('[data-cy=timesheet-1-day-4-minutes]').type('30');

    // submit timesheets
    cy.get('[data-cy=timesheet-submit-timesheet]').click();
    cy.get('[data-cy=timesheet-status-awaiting]').should('exist');

    cy.hasEmployeeLog('Submitted the timesheet of the week started', '/1/employees/1');
  });
});
