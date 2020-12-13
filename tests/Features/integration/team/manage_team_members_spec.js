describe('Team - Members management', function () {
  it('should let users manage members depending on their role', function () {
    cy.loginLegacy();

    cy.createCompany();
    cy.createTeam('product');

    cy.wait(1000);

    cy.visit('/1/teams/1');
    cy.get('[data-cy=manage-team-on]').should('exist');

    cy.changePermission(1, 200);
    cy.visit('/1/teams/1');
    cy.get('[data-cy=manage-team-on]').should('exist');

    cy.changePermission(1, 300);
    cy.visit('/1/teams/1');
    cy.get('[data-cy=manage-team-on]').should('not.exist');
  });

  it('should let you manage team members as an administrator', function () {
    cy.loginLegacy();

    cy.createCompany();
    cy.createTeam('product');
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', false);

    cy.wait(1000);

    cy.visit('/1/teams/1');

    // enable management
    cy.get('[data-cy=manage-team-on]').click();

    // search for an employee
    cy.get('[data-cy=member-input]').type('Michael');
    cy.wait(600);
    cy.get('[data-cy=employee-id-2]').click();

    // does the list contain the newly added member
    cy.get('[data-cy=members-list]').contains('Michael Scott');

    cy.hasAuditLog('Added Michael Scott to product', '/1/teams/1');
    cy.hasTeamLog('Added Michael Scott to the team', '/1/teams/1');

    // remove the employee
    cy.get('[data-cy=manage-team-on]').click();
    cy.get('[data-cy=remove-employee-2]').click();
    cy.get('[data-cy=members-list]').should('not.exist');
    cy.get('[data-cy=members-list-blank-state]').should('exist');
    cy.hasAuditLog('Removed Michael Scott from product', '/1/teams/1');
    cy.hasTeamLog('Removed Michael Scott from the team', '/1/teams/1');
  });

  it('should let you manage team members as an hr', function () {
    cy.loginLegacy();

    cy.createCompany();
    cy.createTeam('product');
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', false);

    cy.wait(1000);
    cy.changePermission(1, 200);

    cy.visit('/1/teams/1');

    // enable management
    cy.get('[data-cy=manage-team-on]').click();

    // search for an employee
    cy.get('[data-cy=member-input]').type('Michael');
    cy.wait(600);
    cy.get('[data-cy=employee-id-2]').click();

    // does the list contain the newly added member
    cy.get('[data-cy=members-list]').contains('Michael Scott');

    cy.hasTeamLog('Added Michael Scott to the team', '/1/teams/1');

    // enable management
    cy.get('[data-cy=manage-team-on]').click();

    // remove the employee
    cy.get('[data-cy=remove-employee-2]').click();
    cy.get('[data-cy=members-list]').should('not.exist');
    cy.get('[data-cy=members-list-blank-state]').should('exist');

    // exit mode
    cy.get('[data-cy=manage-team-off]').click();
    cy.get('[data-cy=manage-team-on]').should('exist');
    cy.get('[data-cy=manage-team-off]').should('not.exist');

    cy.hasTeamLog('Removed Michael Scott from the team', '/1/teams/1');
  });

  it('should not let you manage members as a normal user', function () {
    cy.loginLegacy();

    cy.createCompany();
    cy.createTeam('product');
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', false);

    cy.wait(1000);
    cy.changePermission(1, 300);

    cy.visit('/1/teams/1');
    cy.get('[data-cy=manage-team-on]').should('not.exist');
  });

  it('should indicate who the newest member is', function () {
    cy.loginLegacy();

    cy.createCompany();
    cy.createTeam('product');
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', false);

    cy.wait(1000);
    cy.visit('/1/teams/1');

    cy.get('[data-cy=latest-added-employee-name]').should('not.exist');

    // enable management
    cy.get('[data-cy=manage-team-on]').click();

    // search for an employee and add him
    cy.get('[data-cy=member-input]').type('Michael');
    cy.wait(600);
    cy.get('[data-cy=employee-id-2]').click();

    cy.visit('/1/teams/1');
    cy.get('[data-cy=latest-added-employee-name]').contains('This team has 1 members, the newest being Michael Scott');
  });
});
