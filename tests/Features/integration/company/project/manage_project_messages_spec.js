describe('Project - messages', function () {
  it('should let an employee add a message as administrator', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.createProject(1, 'project 1');

    // write a message
    cy.visit('/1/company/projects/1/messages');
    cy.get('[data-cy=messages-blank-state]').should('exist');

    cy.get('[data-cy=add-message]').click();
    cy.get('[data-cy=message-title-input]').type('message title');
    cy.get('[data-cy=message-content-textarea]').type('message content');
    cy.get('[data-cy=submit-add-message-button]').click();

    cy.get('[data-cy=project-title]').contains('message title');
    cy.get('[data-cy=project-content]').contains('message content');
    cy.hasAuditLog('Added a message called message title', '/1/projects/1/messages/1');

    // edit the message
    cy.get('[data-cy=project-edit]').click();
    cy.get('[data-cy=message-title-input]').clear();
    cy.get('[data-cy=message-title-input]').type('message title');
    cy.get('[data-cy=message-content-textarea]').clear();
    cy.get('[data-cy=message-content-textarea]').type('message content');
    cy.get('[data-cy=submit-update-message-button]').click();

    // delete the message
    cy.get('[data-cy=project-delete]').click();
    cy.get('[data-cy=cancel-project-deletion]').click();
    cy.get('[data-cy=project-delete]').click();
    cy.get('[data-cy=confirm-project-deletion]').click();

    cy.url().should('include', '/messages');
    cy.visit('/1/projects');
  });

  it('should let an employee add a message as HR', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.changePermission(1, 200);

    cy.createProject(1, 'project 1');

    // write a message
    cy.visit('/1/company/projects/1/messages');
    cy.get('[data-cy=messages-blank-state]').should('exist');

    cy.get('[data-cy=add-message]').click();
    cy.get('[data-cy=message-title-input]').type('message title');
    cy.get('[data-cy=message-content-textarea]').type('message content');
    cy.get('[data-cy=submit-add-message-button]').click();

    cy.get('[data-cy=project-title]').contains('message title');
    cy.get('[data-cy=project-content]').contains('message content');

    // edit the message
    cy.get('[data-cy=project-edit]').click();
    cy.get('[data-cy=message-title-input]').clear();
    cy.get('[data-cy=message-title-input]').type('message title');
    cy.get('[data-cy=message-content-textarea]').clear();
    cy.get('[data-cy=message-content-textarea]').type('message content');
    cy.get('[data-cy=submit-update-message-button]').click();

    // delete the message
    cy.get('[data-cy=project-delete]').click();
    cy.get('[data-cy=cancel-project-deletion]').click();
    cy.get('[data-cy=project-delete]').click();
    cy.get('[data-cy=confirm-project-deletion]').click();

    cy.url().should('include', '/messages');
    cy.visit('/1/projects');
  });

  it('should let an employee add a message a project as normal user', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.changePermission(1, 300);

    cy.createProject(1, 'project 1');

    // write a message
    cy.visit('/1/company/projects/1/messages');
    cy.get('[data-cy=messages-blank-state]').should('exist');

    cy.get('[data-cy=add-message]').click();
    cy.get('[data-cy=message-title-input]').type('message title');
    cy.get('[data-cy=message-content-textarea]').type('message content');
    cy.get('[data-cy=submit-add-message-button]').click();

    cy.get('[data-cy=project-title]').contains('message title');
    cy.get('[data-cy=project-content]').contains('message content');

    // edit the message
    cy.get('[data-cy=project-edit]').click();
    cy.get('[data-cy=message-title-input]').clear();
    cy.get('[data-cy=message-title-input]').type('message title');
    cy.get('[data-cy=message-content-textarea]').clear();
    cy.get('[data-cy=message-content-textarea]').type('message content');
    cy.get('[data-cy=submit-update-message-button]').click();

    // delete the message
    cy.get('[data-cy=project-delete]').click();
    cy.get('[data-cy=cancel-project-deletion]').click();
    cy.get('[data-cy=project-delete]').click();
    cy.get('[data-cy=confirm-project-deletion]').click();

    cy.url().should('include', '/messages');
  });
});
