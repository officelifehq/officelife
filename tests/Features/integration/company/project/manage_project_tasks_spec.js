describe('Project - tasks', function () {
  it('should let an employee manage a task as administrator', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.createProject(1, 'project 1');

    // create a task list
    cy.createProjectTaskList(1, 1);

    // edit the list
    cy.get('[data-cy=edit-task-list-1]').click();
    cy.get('[data-cy=task-list-title-input-1]').clear();
    cy.get('[data-cy=task-list-title-input-1]').type('other list');
    cy.get('[data-cy=edit-task-list-cta-1]').click();

    // make sure the list is updated
    cy.get('[data-cy=task-list-1]').contains('other list');

    // add a task to the list
    cy.createProjectTask(1, 1, 1);

    // edit the task
    cy.get('[data-cy=task-1]').trigger('mouseover');
    cy.get('[data-cy=task-1-edit]').click();
    cy.get('[data-cy=edit-task-title-textarea-1]').clear();
    cy.get('[data-cy=edit-task-title-textarea-1]').type('other task name');
    cy.get('[data-cy=edit-task-cta-1]').click();

    // make sure the tsk is updated
    cy.get('[data-cy=task-1]').contains('other task name');

    // delete the task
    cy.get('[data-cy=task-1]').trigger('mouseover');
    cy.get('[data-cy=task-1-delete]').click();
    cy.get('[data-cy=task-1-delete-cta]').click();

    // make sure the task is destroyed
    cy.get('[data-cy=task-1]').should('not.exist');

    // delete the task list
    cy.get('[data-cy=edit-task-list-1]').click();
    cy.get('[data-cy=delete-task-list-cta-1]').click();
    cy.get('[data-cy=task-list-1]').should('not.exist');
  });

  it('should let an employee manage a task as HR', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.changePermission(1, 200);

    cy.createProject(1, 'project 1');

    // create a task list
    cy.createProjectTaskList(1, 1);

    // edit the list
    cy.get('[data-cy=edit-task-list-1]').click();
    cy.get('[data-cy=task-list-title-input-1]').clear();
    cy.get('[data-cy=task-list-title-input-1]').type('other list');
    cy.get('[data-cy=edit-task-list-cta-1]').click();

    // make sure the list is updated
    cy.get('[data-cy=task-list-1]').contains('other list');

    // add a task to the list
    cy.createProjectTask(1, 1, 1);

    // edit the task
    cy.get('[data-cy=task-1]').trigger('mouseover');
    cy.get('[data-cy=task-1-edit]').click();
    cy.get('[data-cy=edit-task-title-textarea-1]').clear();
    cy.get('[data-cy=edit-task-title-textarea-1]').type('other task name');
    cy.get('[data-cy=edit-task-cta-1]').click();

    // make sure the tsk is updated
    cy.get('[data-cy=task-1]').contains('other task name');

    // delete the task
    cy.get('[data-cy=task-1]').trigger('mouseover');
    cy.get('[data-cy=task-1-delete]').click();
    cy.get('[data-cy=task-1-delete-cta]').click();

    // make sure the task is destroyed
    cy.get('[data-cy=task-1]').should('not.exist');

    // delete the task list
    cy.get('[data-cy=edit-task-list-1]').click();
    cy.get('[data-cy=delete-task-list-cta-1]').click();
    cy.get('[data-cy=task-list-1]').should('not.exist');
  });

  it('should let an employee manage a task a project as normal user', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.changePermission(1, 300);

    cy.createProject(1, 'project 1');

    // create a task list
    cy.createProjectTaskList(1, 1);

    // edit the list
    cy.get('[data-cy=edit-task-list-1]').click();
    cy.get('[data-cy=task-list-title-input-1]').clear();
    cy.get('[data-cy=task-list-title-input-1]').type('other list');
    cy.get('[data-cy=edit-task-list-cta-1]').click();

    // make sure the list is updated
    cy.get('[data-cy=task-list-1]').contains('other list');

    // add a task to the list
    cy.createProjectTask(1, 1, 1);

    // edit the task
    cy.get('[data-cy=task-1]').trigger('mouseover');
    cy.get('[data-cy=task-1-edit]').click();
    cy.get('[data-cy=edit-task-title-textarea-1]').clear();
    cy.get('[data-cy=edit-task-title-textarea-1]').type('other task name');
    cy.get('[data-cy=edit-task-cta-1]').click();

    // make sure the tsk is updated
    cy.get('[data-cy=task-1]').contains('other task name');

    // delete the task
    cy.get('[data-cy=task-1]').trigger('mouseover');
    cy.get('[data-cy=task-1-delete]').click();
    cy.get('[data-cy=task-1-delete-cta]').click();

    // make sure the task is destroyed
    cy.get('[data-cy=task-1]').should('not.exist');

    // delete the task list
    cy.get('[data-cy=edit-task-list-1]').click();
    cy.get('[data-cy=delete-task-list-cta-1]').click();
    cy.get('[data-cy=task-list-1]').should('not.exist');
  });
});
