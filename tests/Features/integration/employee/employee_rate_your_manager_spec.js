describe('Employee - rate your manager', function () {
  it('should let a user rates his manager', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true);

    // assign Michael scott as the manager so we can validate a task has been created for the manager later on
    cy.visit('/1/employees/1');
    cy.assignManager('scott');

    // there should not be a performance tab on the manager profile page
    cy.get('[data-cy=employee-tab]').should('not.exist');

    // check that by default, we don’t see a Rate your manager survey on the dashboard
    cy.visit('/1/dashboard/me');
    cy.get('[data-cy=rate-your-manager-survey]').should('not.exist');

    // now launch the setup to rate the manager survey process
    cy.exec('php artisan rateyourmanagerprocess:start');

    // make sure the survey is now displayed on the dashboard
    cy.visit('/1/dashboard/me');
    cy.get('[data-cy=rate-your-manager-survey]').should('exist');

    // vote
    cy.get('[data-cy=log-rate-bad]').click();

    // add a comment
    cy.get('[data-cy=add-comment]').click();
    cy.get('[data-cy=answer-content]').clear();
    cy.get('[data-cy=answer-content]').type('adorable');
    cy.get('[data-cy=answer-not-anonymous]').check();
    cy.get('[data-cy=submit-answer]').click();

    cy.hasAuditLog('Answered the survey about', '/1/employees/1');
    cy.hasEmployeeLog('Answered the survey about', '/1/employees/1');

    // check that the survey is not there anymore
    cy.visit('/1/dashboard/me');
    cy.get('[data-cy=rate-your-manager-survey]').should('not.exist');

    // now, end the survey process and go to see the manager profile page
    cy.exec('php artisan rateyourmanagerprocess:stop --force');
    cy.visit('/1/account/employees/all');
    cy.get('[name=\'Michael Scott\']').invoke('attr', 'data-invitation-link').then((link) => {
      cy.acceptInvitationLinkAndGoToDashboard('admin2020', link);

      cy.visit('/1/employees/2');
      cy.get('[data-cy=performance-tab]').click();
      cy.get('[data-cy=survey-1]').click();
      cy.get('[data-cy=result-bad]').contains('1');
      cy.get('[data-cy=survey-comment]').contains('adorable');

      cy.logout();
    });

    // now log back to the account and make sure the performance tab can’t be seen if you don’t have the right role
    cy.visit('/login');
    cy.get('input[name=email]').type('admin@admin.com');
    cy.get('input[name=password]').type('admin');
    cy.get('button[type=submit]').click();
    cy.get('[data-cy=company-1]').click();

    // the admin can see the performance tab on the profile page
    cy.visit('/1/employees/2');
    cy.get('[data-cy=employee-tab]').should('exist');

    cy.visit('/1/employees/2/performance/surveys');
    cy.url().should('include', '/1/employees/2/performance/surveys');
    cy.visit('/1/employees/2/performance/1');
    cy.url().should('include', '/1/employees/2/performance/1');

    // change permission
    cy.changePermission(1, 200);
    cy.visit('/1/employees/2');
    cy.get('[data-cy=employee-tab]').should('exist');
    cy.visit('/1/employees/2/performance/surveys');
    cy.url().should('include', '/1/employees/2/performance/surveys');
    cy.visit('/1/employees/2/performance/1');
    cy.url().should('include', '/1/employees/2/performance/1');

    // change permission
    cy.changePermission(1, 300);
    cy.visit('/1/employees/2');
    cy.get('[data-cy=employee-tab]').should('not.exist');
    cy.request({
      url: '/1/employees/2/performance/surveys',
      failOnStatusCode: false
    })
      .should((response) => {
        expect(response.status).to.eq(401);
      });
    cy.request({
      url: '/1/employees/2/performance/1',
      failOnStatusCode: false
    })
      .should((response) => {
        expect(response.status).to.eq(401);
      });
  });
});
