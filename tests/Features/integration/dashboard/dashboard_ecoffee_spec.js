describe('Dashboard - employee - ecoffee', function () {
  it('should let the employee participates to an ecoffee match', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.createEmployee('Jim', 'Halpert', 'jim.halpert@dundermifflin.com', 'user', false);

    // make sure nothing is shown on the employee profile for now
    cy.visit('/1/employees/1');
    cy.get('[data-cy=e-coffee-list]').should('not.exist');

    cy.toggleECoffeeProcesss(1, true);

    cy.visit('/1/dashboard/me');

    // check that we've been matched with Jim Halpert
    cy.get('[data-cy=e-coffee-matched-with-name]').should('exist');
    cy.get('[data-cy=e-coffee-matched-with-name]').contains('Jim');
    cy.get('[data-cy=mark-ecoffee-as-happened]').click();

    cy.get('[data-cy=ecoffee-already-participated]').should('exist');

    // check that the information appears on the employee profile now
    cy.visit('/1/employees/1');
    cy.get('[data-cy=e-coffee-list]').should('exist');
  });
});
