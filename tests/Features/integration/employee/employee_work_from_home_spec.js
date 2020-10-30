describe('Employee - work from home', function () {
  it('should let the employee indicates that he works from home', function () {
    cy.loginLegacy();

    cy.createCompany();

    // check that the employee indicates that he doesn't work from home
    cy.visit('/1/employees/1');
    cy.get('[data-cy=work-from-home-today]').should('not.exist');
    cy.get('[data-cy=work-from-home-not-today]').should('exist');
    cy.get('[data-cy=work-from-home-statistics]').should('not.exist');

    cy.visit('/1/dashboard/me');
    cy.get('[data-cy=log-from-work-home-cta]').check();

    cy.visit('/1/employees/1');
    cy.get('[data-cy=work-from-home-today]').should('exist');
    cy.get('[data-cy=work-from-home-not-today]').should('not.exist');
    cy.get('[data-cy=work-from-home-statistics]').should('exist');
  });

  it('should let an hr rep view anothers employee works from home entries', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.visit('/1/dashboard/me');
    cy.get('[data-cy=log-from-work-home-cta]').check();

    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true);
    cy.changePermission(1, 200);

    cy.visit('/1/employees/2');
    cy.get('[data-cy=view-all-work-from-home]').should('exist');
    cy.get('[data-cy=view-all-work-from-home]').click();
    cy.wait(500);
  });

  it('should not let a normal employee view anothers employee works from home entries', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true);
    cy.changePermission(1, 300);

    cy.visit('/1/employees/2');
    cy.get('[data-cy=view-all-work-from-home]').should('not.exist');
  });
});
