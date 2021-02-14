describe('Employee - assign address', function () {
  it('should let an admin edit an address', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true);

    cy.setAddress(1, 2);

    cy.wait(1000);
    cy.visit('/1/account/audit');

    cy.hasAuditLog('Added an address at Montreal', '/1/employees/2');
  });

  it('should let a HR edit an address', function () {
    cy.loginLegacy();

    cy.createCompany();
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true);

    cy.changePermission(1, 200);

    cy.setAddress(1, 2);

    cy.wait(1000);
    cy.url().should('include', '/2');

    cy.visit('/1/employees/2');
  });

  it('should let a normal user edit his own address', function () {
    cy.loginLegacy();

    cy.createCompany();
    cy.changePermission(1, 300);

    cy.setAddress(1, 1);

    cy.wait(1000);
    cy.url().should('include', '/1');

    cy.visit('/1/employees/1');
  });

  it('should not let a normal user edit someone elses address', function () {
    cy.loginLegacy();

    cy.createCompany();
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true);

    cy.changePermission(1, 300);

    cy.visit('/1/employees/2/edit');

    cy.url().should('include', '/home');
  });

  it('should let an admin and an hr see the complete address of an employee and a normal employee see a partial address', function () {
    cy.loginLegacy();

    cy.createCompany();

    // create another employee than the admin
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'user', true);

    cy.setAddress(1, 2);

    cy.wait(1000);

    cy.visit('/1/employees/2');

    // check that the complete address can be seen
    cy.get('[data-cy=employee-location]').contains('Lives in 612 St Jacques St Montreal QC H3C 4M8');

    // change permission to hr and check that the full address still can be seen
    cy.changePermission(1, 200);
    cy.visit('/1/employees/2');
    cy.get('[data-cy=employee-location]').contains('Lives in 612 St Jacques St Montreal QC H3C 4M8');

    cy.changePermission(1, 300);
    cy.visit('/1/employees/2');
    cy.get('[data-cy=employee-location]').contains('Lives in Montreal');
  });

  it('should let an employee sees his complete address', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.setAddress(1, 1);
    cy.wait(1000);

    cy.changePermission(1, 300);
    cy.visit('/1/employees/1');

    // change permission to hr and check that the full address still can be seen
    cy.get('[data-cy=employee-location]').contains('Lives in 612 St Jacques St Montreal QC H3C 4M8');

    cy.hasEmployeeLog('Added an address at Montreal', '/1/employees/1');
  });
});
