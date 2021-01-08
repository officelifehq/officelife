describe('Employee - manage hiring date information', function () {
  it('should let an admin edit hiring date information', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true);

    cy.setBirthdate(1, 2, 'dwight', 'schrute', 'dwight@dundermifflin.com', 1981, 3, 10);
    cy.setHiredDate(1, 2, 1981, 3, 10);

    cy.get('[data-cy=employee-contract-renewal-date]').contains('Mar 10, 1981');

    cy.hasAuditLog('Set the hiring date', '/1/employees/2');
    cy.hasEmployeeLog('Set the hiring date', '/1/employees/2', '/1/employees/2/logs');
  });

  it('should let an HR edit hiring date information', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true);

    cy.changePermission(1, 200);

    cy.setBirthdate(1, 2, 'dwight', 'schrute', 'dwight@dundermifflin.com', 1981, 3, 10);
    cy.setHiredDate(1, 2, 1981, 3, 10);

    cy.get('[data-cy=employee-contract-renewal-date]').contains('Mar 10, 1981');

    cy.hasEmployeeLog('Set the hiring date', '/1/employees/2', '/1/employees/2/logs');
  });

  it('should let not a normal user edit his own hiring date information', function () {
    cy.loginLegacy();

    cy.createCompany();
    cy.changePermission(1, 300);
    cy.visit('/1/employees/1');
    cy.get('[data-cy=edit-important-date-link]').click();
    cy.get('[data-cy=hired-at-information]').should('not.exist');
  });

  it('should not let a normal user edit someone elses hiring date information', function () {
    cy.loginLegacy();

    cy.createCompany();
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true);

    cy.changePermission(1, 300);

    cy.visit('/1/employees/2/edit');

    cy.url().should('include', '/home');
  });
});
