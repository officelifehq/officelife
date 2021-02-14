describe('Employee - Assign managers and direct reports', function () {
  it('should assign a manager and a direct report as administrator', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', false);
    cy.createEmployee('Dwight', 'Schrute', 'dwight.schrute@dundermifflin.com', 'admin', false);
    cy.createEmployee('Jim', 'Halpert', 'jim.halpert@dundermifflin.com', 'admin', false, (id) => {
      cy.visit(`/1/employees/${id}`);
    });

    // i should be now on Jim Halpert page so I will test that I can add
    // michael scott as Jim's manager
    cy.assignManager('scott');
    cy.get('[data-cy=list-managers]').contains('Michael Scott');

    // remove manager
    cy.get('[data-cy=display-remove-manager-modal]').click();
    cy.get('[data-cy=remove-manager-button]').click();
    cy.get('[data-cy=confirm-remove-manager]').click();
    cy.wait(500);
    cy.get('[data-cy=list-managers]').should('not.contain', 'Michael Scott');

    // I will now test that I can add dwight schrute as Jim's direct report
    cy.get('[data-cy=add-hierarchy-button]').click();
    cy.get('[data-cy=add-direct-report-button]').click();
    cy.get('[data-cy=search-direct-report]').type('dwight');
    cy.get('[data-cy=potential-direct-report-button').click();
    cy.get('[data-cy=list-direct-reports]').contains('Dwight Schrute');

    // remove direct report
    cy.get('[data-cy=display-remove-directreport-modal]').click();
    cy.get('[data-cy=remove-directreport-button]').click();
    cy.get('[data-cy=confirm-remove-directreport]').click();
    cy.get('[data-cy=list-direct-reports]').should('not.contain', 'Dwight Schrute');
  });

  it('should assign a manager and a direct report as hr', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', false);
    cy.createEmployee('Dwight', 'Schrute', 'dwight.schrute@dundermifflin.com', 'admin', false);
    cy.createEmployee('Jim', 'Halpert', 'jim.halpert@dundermifflin.com', 'admin', false, (id) => {
      cy.visit(`/1/employees/${id}`);
    });

    cy.changePermission(1, 200);

    // i should be now on Jim Halpert page so I will test that I can add
    // michael scott as Jim's manager
    cy.assignManager('scott');
    cy.get('[data-cy=list-managers]').contains('Michael Scott');

    // remove manager
    cy.get('[data-cy=display-remove-manager-modal]').click();
    cy.get('[data-cy=remove-manager-button]').click();
    cy.get('[data-cy=confirm-remove-manager]').click();
    cy.get('[data-cy=list-managers]').should('not.contain', 'Michael Scott');

    // I will now test that I can add dwight schrute as Jim's direct report
    cy.get('[data-cy=add-hierarchy-button]').click();
    cy.get('[data-cy=add-direct-report-button]').click();
    cy.get('[data-cy=search-direct-report]').type('dwight');
    cy.get('[data-cy=potential-direct-report-button').click();
    cy.get('[data-cy=list-direct-reports]').contains('Dwight Schrute');

    // remove direct report
    cy.get('[data-cy=display-remove-directreport-modal]').click();
    cy.get('[data-cy=remove-directreport-button]').click();
    cy.get('[data-cy=confirm-remove-directreport]').click();
    cy.get('[data-cy=list-direct-reports]').should('not.contain', 'Dwight Schrute');
  });

  it('should give managers a new tab on the dashboard when they become a manager', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', false);
    cy.visit('/1/employees/2');
    cy.assignManager('admin');

    // visit your dashboard
    cy.visit('/1/dashboard');
    cy.get('[data-cy=dashboard-manager-tab]').should('exist');
  });

  it('should check that assigning manager or direct report is reserved for a certain permission level', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.wait(500);

    cy.changePermission(1, 300);
    cy.visit('/1/employees/1');
    cy.get('[data-cy=add-hierarchy-button]').should('not.be.visible');
  });

});
