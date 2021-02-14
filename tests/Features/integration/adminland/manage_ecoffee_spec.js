let faker = require('faker');

describe('Adminland - ECoffee', function () {
  it('should let me manage the ecoffee process as an administrator', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.visit('/1/account/ecoffee');
    cy.get('[data-cy=message-enable]').should('not.exist');
    cy.get('[data-cy=message-disable]').should('exist');

    cy.toggleECoffeeProcesss(1, true);
    cy.get('[data-cy=message-enable]').should('exist');

    cy.toggleECoffeeProcesss(1, false);
    cy.get('[data-cy=message-disable]').should('exist');
  });
});
