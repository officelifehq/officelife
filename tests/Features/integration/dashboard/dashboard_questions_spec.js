describe('Dashboard - employee - Questions/answers', function () {
  it('should let you manage answers of questions', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.get('[data-cy=header-adminland-link]').click();
    cy.get('[data-cy=questions-admin-link]').click();

    //add a question
    cy.get('[data-cy=add-question-button]').click();
    cy.get('[data-cy=add-title-input]').type('this is my question 1');
    cy.get('[data-cy=modal-add-cta]').click();

    cy.wait(1000);

    cy.get('[data-cy=question-activate-link-1]').click();
    cy.get('[data-cy=question-activate-link-confirm-1]').click();

    cy.get('body').invoke('attr', 'data-account-id').then(function (userId) {
      cy.changePermission(userId, 300);
    });

    cy.visit('/1/dashboard/me');

    // check that the answer can be submitted
    cy.get('[data-cy=log-answer-cta]').click();
    cy.get('[data-cy=log-answer-cta]').should('not.be.visible');
    cy.get('[data-cy=answer-content]').type('this is my answer');
    cy.get('[data-cy=submit-answer]').click();
    cy.get('[data-cy=answer-employee-hasnt-answered]').should('not.be.visible');
    cy.get('[data-cy=answer-content-1]').contains('this is my answer');

    // check logs
    cy.hasEmployeeLog('Answered the question called', '/1/dashboard/me');
    cy.get('body').invoke('attr', 'data-account-id').then(function (userId) {
      cy.changePermission(userId, 100);
    });
    cy.hasAuditLog('Answered the question called', '/1/dashboard/me');

    // now edit the answer
    cy.get('body').invoke('attr', 'data-account-id').then(function (userId) {
      cy.changePermission(userId, 300);
    });
    cy.get('[data-cy=answer-edit-link-1]').click();
    cy.get('[data-cy=answer-edit-content]').clear();
    cy.get('[data-cy=answer-edit-content]').type('new answer');
    cy.get('[data-cy=submit-edit-answer]').click();

    cy.get('[data-cy=answer-content-1]').contains('new answer');

    // check logs
    cy.hasEmployeeLog('Updated the answer', '/1/dashboard/me');
    cy.get('body').invoke('attr', 'data-account-id').then(function (userId) {
      cy.changePermission(userId, 100);
    });
    cy.hasAuditLog('Updated the answer', '/1/dashboard/me');

    // now delete the answer
    cy.get('body').invoke('attr', 'data-account-id').then(function (userId) {
      cy.changePermission(userId, 300);
    });
    cy.get('[data-cy=answer-destroy-1]').click();
    cy.get('[data-cy=answer-destroy-confirm-1]').click();

    cy.get('[data-cy=answer-content-1]').should('not.contain', 'new answer');

    // check logs
    cy.hasEmployeeLog('Deleted the answer', '/1/dashboard/me');
    cy.get('body').invoke('attr', 'data-account-id').then(function (userId) {
      cy.changePermission(userId, 100);
    });
    cy.hasAuditLog('Deleted the answer', '/1/dashboard/me');

    cy.get('[data-cy=answer-employee-hasnt-answered]').should('be.visible');
  });
});
