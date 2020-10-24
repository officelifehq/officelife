describe('Dashboard - employee - work from home', function () {
  it('should let the employee indicates that he works from home', function () {
    cy.loginLegacy();

    cy.createCompany();

    // on the dashboard, click on I work from home checkbox
    cy.visit('/1/dashboard/me');
    cy.get('[data-cy=log-from-work-home-cta]').check();

    // reload the page to see the checkbox checked
    cy.visit('/1/dashboard/me');
    cy.get('[data-cy=log-from-work-home-cta]').should('be.checked');

    // toggle the checkbox again
    cy.get('[data-cy=log-from-work-home-cta]').uncheck();

    // reload the page to see the checkbox checked
    cy.visit('/1/dashboard/me');
    cy.get('[data-cy=log-from-work-home-cta]').should('not.be.checked');

    cy.hasAuditLog('Indicated that admin@admin.com has worked from home', '/1/employees/1');
    cy.hasAuditLog('Removed the entry that admin@admin.com has worked from home', '/1/employees/1');
    cy.hasEmployeeLog('Worked from home', '/1/employees/1');
    cy.hasEmployeeLog('Removed the entry about working from home on', '/1/employees/1');
  });
});
