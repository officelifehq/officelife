describe('Project - members', function () {
  it('should let an employee add a member as administrator', function () {
    cy.loginLegacy();

    cy.createCompany();

    // make sure we can create a project with only the name of the project
    cy.createProject(1, 'project 1');

    // add a member without a role
    cy.visit('/1/projects/1/members');
    cy.get('[data-cy=members-blank-state]').should('exist');

    cy.get('[data-cy=member-add-button]').click();
    cy.get('[data-cy=members_selector]').click();
    cy.get('ul.vs__dropdown-menu>li').eq(0).click();
    cy.get('[data-cy=members_selector]').click();
    cy.get('[data-cy=submit-add-member]').click();
    cy.get('[data-cy=member-1]').contains('admin@admin.com');
    cy.get('[data-cy=members-blank-state]').should('not.exist');
    cy.hasAuditLog('Added ', '/1/projects/1/members');

    // remove the member
    cy.get('[data-cy=member-delete-1]').click();
    cy.get('[data-cy=list-delete-cancel-button-1]').click();
    cy.get('[data-cy=member-delete-1]').click();
    cy.get('[data-cy=list-delete-confirm-button-1]').click();
    cy.get('[data-cy=members-blank-state]').should('exist');

    // add a member with a role
    cy.get('[data-cy=member-add-button]').click();
    cy.get('[data-cy=members_selector]').click();
    cy.get('ul.vs__dropdown-menu>li').eq(0).click();
    cy.get('[data-cy=members_selector]').click();
    cy.get('[data-cy=custom-role-field]').click();
    cy.get('[data-cy=customRole]').type('custom role');
    cy.get('[data-cy=submit-add-member]').click();

    cy.get('[data-cy=member-1]').contains('admin@admin.com');
    cy.get('[data-cy=member-1]').contains('custom role');

    cy.get('[data-cy=members-blank-state]').should('not.exist');
    cy.hasAuditLog('Added ', '/1/projects/1/members');
  });

  it('should let an employee add a member as HR', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.changePermission(1, 200);

    // make sure we can create a project with only the name of the project
    cy.createProject(1, 'project 1');

    // add a member without a role
    cy.visit('/1/projects/1/members');
    cy.get('[data-cy=members-blank-state]').should('exist');

    cy.get('[data-cy=member-add-button]').click();
    cy.get('[data-cy=members_selector]').click();
    cy.get('ul.vs__dropdown-menu>li').eq(0).click();
    cy.get('[data-cy=members_selector]').click();
    cy.get('[data-cy=submit-add-member]').click();
    cy.get('[data-cy=member-1]').contains('admin@admin.com');
    cy.get('[data-cy=members-blank-state]').should('not.exist');

    // remove the member
    cy.get('[data-cy=member-delete-1]').click();
    cy.get('[data-cy=list-delete-cancel-button-1]').click();
    cy.get('[data-cy=member-delete-1]').click();
    cy.get('[data-cy=list-delete-confirm-button-1]').click();
    cy.get('[data-cy=members-blank-state]').should('exist');

    // add a member with a role
    cy.get('[data-cy=member-add-button]').click();
    cy.get('[data-cy=members_selector]').click();
    cy.get('ul.vs__dropdown-menu>li').eq(0).click();
    cy.get('[data-cy=members_selector]').click();
    cy.get('[data-cy=custom-role-field]').click();
    cy.get('[data-cy=customRole]').type('custom role');
    cy.get('[data-cy=submit-add-member]').click();

    cy.get('[data-cy=member-1]').contains('admin@admin.com');
    cy.get('[data-cy=member-1]').contains('custom role');

    cy.get('[data-cy=members-blank-state]').should('not.exist');
  });

  it('should let an employee add a member a project as normal user', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.changePermission(1, 300);

    // make sure we can create a project with only the name of the project
    cy.createProject(1, 'project 1');

    // add a member without a role
    cy.visit('/1/projects/1/members');
    cy.get('[data-cy=members-blank-state]').should('exist');

    cy.get('[data-cy=member-add-button]').click();
    cy.get('[data-cy=members_selector]').click();
    cy.get('ul.vs__dropdown-menu>li').eq(0).click();
    cy.get('[data-cy=members_selector]').click();
    cy.get('[data-cy=submit-add-member]').click();
    cy.get('[data-cy=member-1]').contains('admin@admin.com');
    cy.get('[data-cy=members-blank-state]').should('not.exist');

    // remove the member
    cy.get('[data-cy=member-delete-1]').click();
    cy.get('[data-cy=list-delete-cancel-button-1]').click();
    cy.get('[data-cy=member-delete-1]').click();
    cy.get('[data-cy=list-delete-confirm-button-1]').click();
    cy.get('[data-cy=members-blank-state]').should('exist');

    // add a member with a role
    cy.get('[data-cy=member-add-button]').click();
    cy.get('[data-cy=members_selector]').click();
    cy.get('ul.vs__dropdown-menu>li').eq(0).click();
    cy.get('[data-cy=members_selector]').click();
    cy.get('[data-cy=custom-role-field]').click();
    cy.get('[data-cy=customRole]').type('custom role');
    cy.get('[data-cy=submit-add-member]').click();

    cy.get('[data-cy=member-1]').contains('admin@admin.com');
    cy.get('[data-cy=member-1]').contains('custom role');

    cy.get('[data-cy=members-blank-state]').should('not.exist');
  });
});
