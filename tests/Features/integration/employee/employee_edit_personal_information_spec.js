describe('Employee - edit personal information', function () {
  it('should let an admin edit personal information', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true);
    cy.visit('/1/employees/2');

    cy.setBirthdate(1, 2, 'dwight', 'schrute', 'dwight@dundermifflin.com', 1981, 3, 10);

    cy.url().should('include', '/2');

    cy.hasAuditLog('Set the employee name and email address', '/1/employees/2');

    cy.visit('/1/employees/2/logs');
    cy.contains('Set the name and email to');
  });

  it('should let a HR edit personal information', function () {
    cy.loginLegacy();

    cy.createCompany();
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true);

    cy.changePermission(1, 200);

    cy.setBirthdate(1, 2, 'dwight', 'schrute', 'dwight@dundermifflin.com', 1981, 3, 10);
    cy.url().should('include', '/2');

    cy.visit('/1/employees/2/logs');
    cy.contains('Set the name and email to');

    cy.visit('/1/employees/2');
  });

  it('should let a normal user edit his own information', function () {
    cy.loginLegacy();

    cy.createCompany();
    cy.changePermission(1, 300);

    cy.setBirthdate(1, 1, 'dwight', 'schrute', 'dwight@dundermifflin.com', 1981, 3, 10);

    cy.visit('/1/employees/1/logs');
    cy.contains('Set the name and email to');
  });

  it('should not let a normal user edit someone elses information', function () {
    cy.loginLegacy();

    cy.createCompany();
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true);

    cy.changePermission(1, 300);

    cy.visit('/1/employees/2/edit');

    cy.url().should('include', '/home');
  });

  it('should let an admin edit twitter information', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true);

    cy.setBirthdate(1, 2, 'dwight', 'schrute', 'dwight@dundermifflin.com', 1981, 3, 10);
    cy.setTwitterAccount(1, 2, 'dwight');

    cy.wait(500);
    cy.url().should('include', 'employees/2');
    cy.get('[data-cy=employee-twitter-handle]').contains('dwight');

    cy.hasAuditLog('Set Twitter handle’s of', '/1/employees/2');
    cy.hasEmployeeLog('Set Twitter handle’s to dwight', '/1/dashboard', '1/employees/2/logs');

    // reset the twitter handle
    cy.setTwitterAccount(1, 2);
    cy.url().should('include', '/2');

    cy.get('[data-cy=employee-twitter-handle]').should('not.exist');

    cy.hasAuditLog('Reset Twitter handle', '/1/employees/2');
    cy.hasEmployeeLog('Reset Twitter handle', '/1/dashboard', '1/employees/2/logs');
  });

  it('should let an HR edit twitter information', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true);

    cy.changePermission(1, 200);

    cy.setBirthdate(1, 2, 'dwight', 'schrute', 'dwight@dundermifflin.com', 1981, 3, 10);
    cy.setTwitterAccount(1, 2, 'dwight');

    cy.wait(500);
    cy.url().should('include', 'employees/2');

    cy.hasEmployeeLog('Set Twitter handle’s to dwight', '/1/dashboard', '1/employees/2/logs');

    // reset the twitter handle
    cy.setTwitterAccount(1, 2);
    cy.wait(500);
    cy.url().should('include', 'employees/2');

    cy.get('[data-cy=employee-twitter-handle]').should('not.exist');

    cy.hasEmployeeLog('Reset Twitter handle', '/1/dashboard', '1/employees/2/logs');
  });

  it('should let a normal user edit his own twitter information', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.changePermission(1, 300);

    cy.setBirthdate(1, 1, 'dwight', 'schrute', 'dwight@dundermifflin.com', 1981, 3, 10);
    cy.setTwitterAccount(1, 1, 'dwight');
    cy.wait(500);

    cy.hasEmployeeLog('Set Twitter handle’s to dwight', '/1/dashboard', '1/employees/1/logs');

    // reset the twitter handle
    cy.setTwitterAccount(1, 1);
    cy.wait(500);

    cy.get('[data-cy=employee-twitter-handle]').should('not.exist');

    cy.hasEmployeeLog('Reset Twitter handle', '/1/dashboard');
  });

  it('should let an admin edit slack information', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true);

    cy.setBirthdate(1, 2, 'dwight', 'schrute', 'dwight@dundermifflin.com', 1981, 3, 10);
    cy.setSlackAccount(1, 2, 'dwight');

    cy.wait(500);
    cy.url().should('include', '/2');

    cy.get('[data-cy=employee-slack-handle]').contains('dwight');

    cy.hasAuditLog('Set Slack handle’s of', '/1/employees/2');
    cy.hasEmployeeLog('Set Slack handle’s to dwight', '/1/dashboard', '1/employees/2/logs');

    // reset the twitter handle
    cy.setSlackAccount(1, 2);
    cy.url().should('include', '/2');

    cy.get('[data-cy=employee-slack-handle]').should('not.exist');

    cy.hasAuditLog('Reset Slack handle', '/1/employees/2');
    cy.hasEmployeeLog('Reset Slack handle', '/1/dashboard', '1/employees/2/logs');
  });

  it('should let an HR edit slack information', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true);

    cy.changePermission(1, 200);

    cy.setBirthdate(1, 2, 'dwight', 'schrute', 'dwight@dundermifflin.com', 1981, 3, 10);
    cy.setSlackAccount(1, 2, 'dwight');

    cy.wait(500);
    cy.url().should('include', '/2');

    cy.hasEmployeeLog('Set Slack handle’s to dwight', '/1/dashboard', '1/employees/2/logs');

    // reset the slack handle
    cy.setSlackAccount(1, 2);
    cy.wait(500);
    cy.url().should('include', '/2');

    cy.get('[data-cy=employee-slack-handle]').should('not.exist');

    cy.hasEmployeeLog('Reset Slack handle', '/1/dashboard', '1/employees/2/logs');
  });

  it('should let a normal user edit his own slack information', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.changePermission(1, 300);

    cy.setBirthdate(1, 1, 'dwight', 'schrute', 'dwight@dundermifflin.com', 1981, 3, 10);
    cy.setSlackAccount(1, 1, 'dwight');
    cy.wait(500);

    cy.hasEmployeeLog('Set Slack handle’s to dwight', '/1/dashboard', '1/employees/1/logs');

    // reset the slack handle
    cy.setSlackAccount(1, 1);
    cy.wait(500);

    cy.get('[data-cy=employee-slack-handle]').should('not.exist');

    cy.hasEmployeeLog('Reset Slack handle', '/1/dashboard');
  });
});
