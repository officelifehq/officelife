describe('Project - decisions', function () {
  it('should let an employee manage decisions as administrator', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.createProject(1, 'project 1');

    // log a decision
    cy.visit('/1/company/projects/1/decisions');
    cy.get('[data-cy=decision-blank-state]').should('exist');

    // add a decision without a decider
    cy.get('[data-cy=add-decision]').click();
    cy.get('[data-cy=decision-title-input]').type('This is a decision');
    cy.get('[data-cy=submit-add-decision-button]').click();
    cy.get('[data-cy=decision-1]').contains('This is a decision');

    // delete the decision
    cy.get('[data-cy=decision-display-menu-1]').click();
    cy.get('[data-cy=decision-delete-1]').click();
    cy.get('[data-cy=decision-delete-confirmation-1]').click();
    cy.get('[data-cy=decision-blank-state]').should('exist');
  });

  it('should let an employee manage decisions as HR', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.createProject(1, 'project 1');

    cy.changePermission(1, 200);

    // log a decision
    cy.visit('/1/company/projects/1/decisions');
    cy.get('[data-cy=decision-blank-state]').should('exist');

    // add a decision without a decider
    cy.get('[data-cy=add-decision]').click();
    cy.get('[data-cy=decision-title-input]').type('This is a decision');
    cy.get('[data-cy=submit-add-decision-button]').click();
    cy.get('[data-cy=decision-1]').contains('This is a decision');

    // delete the decision
    cy.get('[data-cy=decision-display-menu-1]').click();
    cy.get('[data-cy=decision-delete-1]').click();
    cy.get('[data-cy=decision-delete-confirmation-1]').click();
    cy.get('[data-cy=decision-blank-state]').should('exist');
  });

  it('should let an employee manage decisions as normal user', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.createProject(1, 'project 1');

    cy.changePermission(1, 300);

    // log a decision
    cy.visit('/1/company/projects/1/decisions');
    cy.get('[data-cy=decision-blank-state]').should('exist');

    // add a decision without a decider
    cy.get('[data-cy=add-decision]').click();
    cy.get('[data-cy=decision-title-input]').type('This is a decision');
    cy.get('[data-cy=submit-add-decision-button]').click();
    cy.get('[data-cy=decision-1]').contains('This is a decision');

    // delete the decision
    cy.get('[data-cy=decision-display-menu-1]').click();
    cy.get('[data-cy=decision-delete-1]').click();
    cy.get('[data-cy=decision-delete-confirmation-1]').click();
    cy.get('[data-cy=decision-blank-state]').should('exist');
  });
});
