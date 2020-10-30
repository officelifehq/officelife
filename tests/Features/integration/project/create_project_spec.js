describe('Project - project creation', function () {
  it('should let an employee create a project as administrator', function () {
    cy.loginLegacy();

    cy.createCompany();

    // make sure we can create a project with only the name of the project
    cy.createProject(1, 'project 1');
    cy.url().should('include', '/1/projects/1');

    // make sure we can create a project name + code
    cy.createProject(1, 'project 2', 'code project 2');
    cy.url().should('include', '/1/projects/2');

    // make sure we can create a project name + code + summary
    cy.createProject(1, 'project 3', 'code project 3', 'summary project 3');
    cy.url().should('include', '/1/projects/3');

    // make sure we can create a project name + code + summary + project lead
    cy.createProject(1, 'project 4', 'code project 4', 'summary project 4', 1);
    cy.url().should('include', '/1/projects/4');

    cy.hasAuditLog('Created the project called project 4', '/1/projects/4');

    // edit the project
    cy.get('[data-cy=project-edit]').click();
    cy.url().should('include', '/1/projects/4/edit');
    cy.get('[data-cy=project-name-input]').type('new project name');
    cy.get('[data-cy=project-code-input]').type('code');
    cy.get('[data-cy=project-summary-input]').type('summary');
    cy.get('[data-cy=submit-edit-project-button]').click();

    cy.get('[data-cy=project-name]').contains('new project name');
    cy.hasAuditLog('Updated project information for the project called project 4', '/1/projects/4');

    // change project status
    cy.get('[data-cy=project-status]').contains('Not started');
    cy.get('[data-cy=start-project]').click();
    cy.get('[data-cy=project-status]').contains('Active');
    cy.hasAuditLog('Started the project called', '/1/projects/4');
    cy.get('[data-cy=pause-project]').click();
    cy.get('[data-cy=project-status]').contains('Paused');
    cy.hasAuditLog('Put the project called', '/1/projects/4');
    cy.get('[data-cy=unpause-project]').click();
    cy.get('[data-cy=project-status]').contains('Active');
    cy.hasAuditLog('Started the project called', '/1/projects/4');
    cy.get('[data-cy=close-project]').click();
    cy.get('[data-cy=project-status]').contains('Completed');
    cy.hasAuditLog('Closed the project called', '/1/projects/4');
    cy.get('[data-cy=start-project]').click();
    cy.get('[data-cy=project-status]').contains('Active');

    // update project description
    cy.get('[data-cy=add-description-button]').click();
    cy.get('[data-cy=description-textarea]').type('this is a description');
    cy.get('[data-cy=submit-add-description]').click();
    cy.hasAuditLog('Updated the description of the project called', '/1/projects/4');

    cy.get('[data-cy=add-description-button]').click();
    cy.get('[data-cy=clear-description]').click();

    // add project lead
    cy.createProject(1, 'project 5');
    cy.get('[data-cy=add-project-lead-blank-state]').click();
    cy.get('[data-cy=search-project-lead]').type('admin');
    cy.get('[data-cy=potential-project-lead-1]').click();
    cy.get('[data-cy=current-project-lead]').contains('admin');

    // remove project lead
    cy.get('[data-cy=display-remove-project-lead-modal]').click();
    cy.get('[data-cy=remove-project-lead-button]').click();
    cy.get('[data-cy=confirm-remove-project-lead]').click();
    cy.get('[data-cy=add-project-lead-blank-state]').should('exist');
  });

  it('should create a project as hr', function () {
    cy.loginLegacy();

    cy.createCompany();
    cy.changePermission(1, 200);

    // make sure we can create a project with only the name of the project
    cy.createProject(1, 'project 1');
    cy.url().should('include', '/1/projects/1');

    // make sure we can create a project name + code
    cy.createProject(1, 'project 2', 'code project 2');
    cy.url().should('include', '/1/projects/2');

    // make sure we can create a project name + code + summary
    cy.createProject(1, 'project 3', 'code project 3', 'summary project 3');
    cy.url().should('include', '/1/projects/3');

    // make sure we can create a project name + code + summary + project lead
    cy.createProject(1, 'project 4', 'code project 4', 'summary project 4', 1);
    cy.url().should('include', '/1/projects/4');

    // edit the project
    cy.get('[data-cy=project-edit]').click();
    cy.url().should('include', '/1/projects/4/edit');
    cy.get('[data-cy=project-name-input]').type('new project name');
    cy.get('[data-cy=project-code-input]').type('code');
    cy.get('[data-cy=project-summary-input]').type('summary');
    cy.get('[data-cy=submit-edit-project-button]').click();

    cy.get('[data-cy=project-name]').contains('new project name');

    // change project status
    cy.get('[data-cy=project-status]').contains('Not started');
    cy.get('[data-cy=start-project]').click();
    cy.get('[data-cy=project-status]').contains('Active');
    cy.get('[data-cy=pause-project]').click();
    cy.get('[data-cy=project-status]').contains('Paused');
    cy.get('[data-cy=unpause-project]').click();
    cy.get('[data-cy=project-status]').contains('Active');
    cy.get('[data-cy=close-project]').click();
    cy.get('[data-cy=project-status]').contains('Completed');
    cy.get('[data-cy=start-project]').click();
    cy.get('[data-cy=project-status]').contains('Active');

    // update project description
    cy.get('[data-cy=add-description-button]').click();
    cy.get('[data-cy=description-textarea]').type('this is a description');
    cy.get('[data-cy=submit-add-description]').click();

    cy.get('[data-cy=add-description-button]').click();
    cy.get('[data-cy=clear-description]').click();

    // add project lead
    cy.createProject(1, 'project 5');
    cy.get('[data-cy=add-project-lead-blank-state]').click();
    cy.get('[data-cy=search-project-lead]').type('admin');
    cy.get('[data-cy=potential-project-lead-1]').click();
    cy.get('[data-cy=current-project-lead]').contains('admin');

    // remove project lead
    cy.get('[data-cy=display-remove-project-lead-modal]').click();
    cy.get('[data-cy=remove-project-lead-button]').click();
    cy.get('[data-cy=confirm-remove-project-lead]').click();
    cy.get('[data-cy=add-project-lead-blank-state]').should('exist');
  });

  it('should create a project as normal user', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.changePermission(1, 300);

    // make sure we can create a project with only the name of the project
    cy.createProject(1, 'project 1');
    cy.url().should('include', '/1/projects/1');

    // make sure we can create a project name + code
    cy.createProject(1, 'project 2', 'code project 2');
    cy.url().should('include', '/1/projects/2');

    // make sure we can create a project name + code + summary
    cy.createProject(1, 'project 3', 'code project 3', 'summary project 3');
    cy.url().should('include', '/1/projects/3');

    // make sure we can create a project name + code + summary + project lead
    cy.createProject(1, 'project 4', 'code project 4', 'summary project 4', 1);
    cy.url().should('include', '/1/projects/4');

    // edit the project
    cy.get('[data-cy=project-edit]').click();
    cy.url().should('include', '/1/projects/4/edit');
    cy.get('[data-cy=project-name-input]').type('new project name');
    cy.get('[data-cy=project-code-input]').type('code');
    cy.get('[data-cy=project-summary-input]').type('summary');
    cy.get('[data-cy=submit-edit-project-button]').click();

    cy.get('[data-cy=project-name]').contains('new project name');

    // change project status
    cy.get('[data-cy=project-status]').contains('Not started');
    cy.get('[data-cy=start-project]').click();
    cy.get('[data-cy=project-status]').contains('Active');
    cy.get('[data-cy=pause-project]').click();
    cy.get('[data-cy=project-status]').contains('Paused');
    cy.get('[data-cy=unpause-project]').click();
    cy.get('[data-cy=project-status]').contains('Active');
    cy.get('[data-cy=close-project]').click();
    cy.get('[data-cy=project-status]').contains('Completed');
    cy.get('[data-cy=start-project]').click();
    cy.get('[data-cy=project-status]').contains('Active');

    // update project description
    cy.get('[data-cy=add-description-button]').click();
    cy.get('[data-cy=description-textarea]').type('this is a description');
    cy.get('[data-cy=submit-add-description]').click();

    cy.get('[data-cy=add-description-button]').click();
    cy.get('[data-cy=clear-description]').click();

    // add project lead
    cy.createProject(1, 'project 5');
    cy.get('[data-cy=add-project-lead-blank-state]').click();
    cy.get('[data-cy=search-project-lead]').type('admin');
    cy.get('[data-cy=potential-project-lead-1]').click();
    cy.get('[data-cy=current-project-lead]').contains('admin');

    // remove project lead
    cy.get('[data-cy=display-remove-project-lead-modal]').click();
    cy.get('[data-cy=remove-project-lead-button]').click();
    cy.get('[data-cy=confirm-remove-project-lead]').click();
    cy.get('[data-cy=add-project-lead-blank-state]').should('exist');
  });
});
