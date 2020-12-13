describe('Employee - worklogs', function () {
  it('should let the employee sees a worklog', function () {
    cy.loginLegacy();

    cy.createCompany();

    // there should be no worklogs
    cy.visit('/1/employees/1');

    cy.get('body').should('contain', 'No work log this day');
    cy.get('body').should('not.contain', 'I made a drawing');

    cy.visit('/1/dashboard');

    // find the worklog tab, enter the text and press save
    cy.get('[data-cy=log-worklog-cta]').click();
    cy.get('[data-cy=worklog-content]').type('I made a drawing');
    cy.get('[data-cy=submit-log-worklog]').click();

    cy.visit('/1/employees/1');

    cy.get('body').should('contain', 'I made a drawing');

    // see the complete worklog page
    cy.get('[data-cy=view-all-worklogs]').click();
    cy.url().should('include', '/1/employees/1/worklogs');
  });
});
