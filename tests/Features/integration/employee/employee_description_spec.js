describe('Employee - description', function () {
  it('should let an employee update his personal description', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.visit('/1/employees/1');

    // test the cancel button
    cy.get('[data-cy=add-description-button]').click();
    cy.get('[data-cy=cancel-add-description]').click();

    // update for real
    cy.get('[data-cy=add-description-button]').click();
    cy.get('[data-cy=description-textarea').type('Prank lover');
    cy.get('[data-cy=submit-add-description]').click();

    cy.visit('/1/employees/1');
    cy.contains('Prank lover');

    cy.hasAuditLog('Set a personal description to admin@admin.com', '/1/employees/1');
    cy.hasEmployeeLog('Set a personal description', '/1/employees/1');
  });

  it('should let an admin update the personal description of an employee', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true);
    cy.visit('/1/employees/2');

    // test the cancel button
    cy.get('[data-cy=add-description-button]').click();
    cy.get('[data-cy=cancel-add-description]').click();

    // update for real
    cy.get('[data-cy=add-description-button]').click();
    cy.get('[data-cy=description-textarea').type('Prank lover');
    cy.get('[data-cy=submit-add-description]').click();

    cy.visit('/1/employees/2');
    cy.contains('Prank lover');
  });

  it('should let an HR update the personal description of an employee', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true);
    cy.visit('/1/employees/2');

    cy.changePermission(1, 200);

    // test the cancel button
    cy.get('[data-cy=add-description-button]').click();
    cy.get('[data-cy=cancel-add-description]').click();

    // update for real
    cy.get('[data-cy=add-description-button]').click();
    cy.get('[data-cy=description-textarea').type('Prank lover');
    cy.get('[data-cy=submit-add-description]').click();

    cy.visit('/1/employees/2');
    cy.contains('Prank lover');
  });

  it('should let not a normal employee update another employee description', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true);

    cy.changePermission(1, 300);

    cy.visit('/1/employees/2');
    cy.get('[data-cy=add-description-button]').should('not.visible');
  });
});
