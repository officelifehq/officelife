describe('Company - Questions', function () {
  it('should let you see all questions on the company page an administrator', function () {
    cy.loginLegacy();

    cy.createCompany();

    // first we should create a question
    cy.get('[data-cy=header-adminland-link]').click();
    cy.get('[data-cy=questions-admin-link]').click();
    cy.get('[data-cy=add-question-button]').click();
    cy.get('[data-cy=add-title-input]').type('this is my question 1');
    cy.get('[data-cy=modal-add-cta]').click();

    cy.wait(1000);

    // mark the status of the question as active
    cy.get('[data-cy=question-activate-link-1]').click();
    cy.get('[data-cy=question-activate-link-confirm-1]').click();

    // then, answer the question on the dashboard
    cy.visit('/1/dashboard/me');
    cy.get('[data-cy=log-answer-cta]').click();
    cy.get('[data-cy=answer-content]').type('this is my answer');
    cy.get('[data-cy=submit-answer]').click();

    // then, go back to the adminland and check that the number of answers has
    // increased
    cy.get('[data-cy=header-adminland-link]').click();
    cy.get('[data-cy=questions-admin-link]').click();
    cy.get('[data-cy=question-number-of-answers-1]').contains('1 answer');

    // now go to the Questions page on the Company page by clicking on the number of answers
    cy.get('[data-cy=question-number-of-answers-1]').click();
    cy.get('[data-cy=company-question-title]').should('exist');
    cy.contains('this is my answer');

    // then we add another question but we shouldn't be able to see it in the list of questions on the company page
    cy.get('[data-cy=header-adminland-link]').click();
    cy.get('[data-cy=questions-admin-link]').click();
    cy.get('[data-cy=add-question-button]').click();
    cy.get('[data-cy=add-title-input]').type('this is my question 2');
    cy.get('[data-cy=modal-add-cta]').click();
    cy.visit('/1/company/questions');
    cy.get('[data-cy=list-question-2]').should('not.exist');
  });

  it('should let you see all questions on the company page an HR', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.get('body').invoke('attr', 'data-account-id').then(function (userId) {
      cy.changePermission(userId, 200);
    });

    // first we should create a question
    cy.get('[data-cy=header-adminland-link]').click();
    cy.get('[data-cy=questions-admin-link]').click();
    cy.get('[data-cy=add-question-button]').click();
    cy.get('[data-cy=add-title-input]').type('this is my question 1');
    cy.get('[data-cy=modal-add-cta]').click();

    cy.wait(1000);

    // mark the status of the question as active
    cy.get('[data-cy=question-activate-link-1]').click();
    cy.get('[data-cy=question-activate-link-confirm-1]').click();

    // then, answer the question on the dashboard
    cy.visit('/1/dashboard/me');
    cy.get('[data-cy=log-answer-cta]').click();
    cy.get('[data-cy=answer-content]').type('this is my answer');
    cy.get('[data-cy=submit-answer]').click();

    // then, go back to the adminland and check that the number of answers has
    // increased
    cy.get('[data-cy=header-adminland-link]').click();
    cy.get('[data-cy=questions-admin-link]').click();
    cy.get('[data-cy=question-number-of-answers-1]').contains('1 answer');

    // now go to the Questions page on the Company page by clicking on the number of answers
    cy.get('[data-cy=question-number-of-answers-1]').click();
    cy.get('[data-cy=company-question-title]').should('exist');
    cy.contains('this is my answer');

    // then we add another question but we shouldn't be able to see it in the list of questions on the company page
    cy.get('[data-cy=header-adminland-link]').click();
    cy.get('[data-cy=questions-admin-link]').click();
    cy.get('[data-cy=add-question-button]').click();
    cy.get('[data-cy=add-title-input]').type('this is my question 2');
    cy.get('[data-cy=modal-add-cta]').click();
    cy.visit('/1/company/questions');
    cy.get('[data-cy=list-question-2]').should('not.exist');
  });

  it('should let you see all questions on the company page a normal user', function () {
    cy.loginLegacy();

    cy.createCompany();

    // first we should create a question
    cy.get('[data-cy=header-adminland-link]').click();
    cy.get('[data-cy=questions-admin-link]').click();
    cy.get('[data-cy=add-question-button]').click();
    cy.get('[data-cy=add-title-input]').type('this is my question 1');
    cy.get('[data-cy=modal-add-cta]').click();

    cy.wait(1000);

    // mark the status of the question as active
    cy.get('[data-cy=question-activate-link-1]').click();
    cy.get('[data-cy=question-activate-link-confirm-1]').click();

    // then, answer the question on the dashboard
    cy.visit('/1/dashboard/me');
    cy.get('[data-cy=log-answer-cta]').click();
    cy.get('[data-cy=answer-content]').type('this is my answer');
    cy.get('[data-cy=submit-answer]').click();

    // then, go back to the adminland and check that the number of answers has
    // increased
    cy.get('[data-cy=header-adminland-link]').click();
    cy.get('[data-cy=questions-admin-link]').click();
    cy.get('[data-cy=question-number-of-answers-1]').contains('1 answer');

    cy.get('body').invoke('attr', 'data-account-id').then(function (userId) {
      cy.changePermission(userId, 300);
    });
    cy.visit('/1/company/questions/1');
    cy.get('[data-cy=company-question-title]').should('exist');
    cy.contains('this is my answer');

    // then we add another question but we shouldn't be able to see it in the list of questions on the company page
    cy.get('body').invoke('attr', 'data-account-id').then(function (userId) {
      cy.changePermission(userId, 100);
    });
    cy.get('[data-cy=header-adminland-link]').click();
    cy.get('[data-cy=questions-admin-link]').click();
    cy.get('[data-cy=add-question-button]').click();
    cy.get('[data-cy=add-title-input]').type('this is my question 2');
    cy.get('[data-cy=modal-add-cta]').click();

    cy.get('body').invoke('attr', 'data-account-id').then(function (userId) {
      cy.changePermission(userId, 300);
    });
    cy.visit('/1/company/questions');
    cy.get('[data-cy=list-question-2]').should('not.exist');
  });
});
