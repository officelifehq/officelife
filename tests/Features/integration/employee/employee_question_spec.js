describe('Employee - Questions', function () {
  it('should let you see all questions answered by an employee as an administrator', function () {
    cy.loginLegacy()

    cy.createCompany()

    // check that the employee tab doesnt contain any answer
    cy.visit('/1/employees/1')
    cy.get('[data-cy=question-blank-state]').should('exist')

    // first we should create a question
    cy.get('[data-cy=header-adminland-link]').click()
    cy.get('[data-cy=questions-admin-link]').click()
    cy.get('[data-cy=add-question-button]').click()
    cy.get('[data-cy=add-title-input]').type('this is my question 1')
    cy.get('[data-cy=modal-add-cta]').click()

    cy.wait(1000)

    // mark the status of the question as active
    cy.get('[data-cy=question-activate-link-1]').click()
    cy.get('[data-cy=question-activate-link-confirm-1]').click()

    // then, answer the question on the dashboard
    cy.visit('/1/dashboard/me')
    cy.get('[data-cy=log-answer-cta]').click()
    cy.get('[data-cy=answer-content]').type('this is my answer')
    cy.get('[data-cy=submit-answer]').click()

    // then, go to the employee page
    cy.visit('/1/employees/1')
    cy.get('[data-cy=question-blank-state]').should('not.exist')
    cy.get('[data-cy=question-title-1]').contains('this is my question 1')
    cy.get('[data-cy=answer-body-1]').contains('this is my answer')
  })

  it('should let you see all questions answered by an employee as an HR', function () {
    cy.loginLegacy()

    cy.createCompany()

    cy.changePermission(1, 200)

    // check that the employee tab doesnt contain any answer
    cy.visit('/1/employees/1')
    cy.get('[data-cy=question-blank-state]').should('exist')

    // first we should create a question
    cy.get('[data-cy=header-adminland-link]').click()
    cy.get('[data-cy=questions-admin-link]').click()
    cy.get('[data-cy=add-question-button]').click()
    cy.get('[data-cy=add-title-input]').type('this is my question 1')
    cy.get('[data-cy=modal-add-cta]').click()

    cy.wait(1000)

    // mark the status of the question as active
    cy.get('[data-cy=question-activate-link-1]').click()
    cy.get('[data-cy=question-activate-link-confirm-1]').click()

    // then, answer the question on the dashboard
    cy.visit('/1/dashboard/me')
    cy.get('[data-cy=log-answer-cta]').click()
    cy.get('[data-cy=answer-content]').type('this is my answer')
    cy.get('[data-cy=submit-answer]').click()

    // then, go to the employee page
    cy.visit('/1/employees/1')
    cy.get('[data-cy=question-blank-state]').should('not.exist')
    cy.get('[data-cy=question-title-1]').contains('this is my question 1')
    cy.get('[data-cy=answer-body-1]').contains('this is my answer')
  })

  it('should let you see all questions answered by an employee as a normal user', function () {
    cy.loginLegacy()

    cy.createCompany()

    // check that the employee tab doesnt contain any answer
    cy.visit('/1/employees/1')
    cy.get('[data-cy=question-blank-state]').should('exist')

    // first we should create a question
    cy.get('[data-cy=header-adminland-link]').click()
    cy.get('[data-cy=questions-admin-link]').click()
    cy.get('[data-cy=add-question-button]').click()
    cy.get('[data-cy=add-title-input]').type('this is my question 1')
    cy.get('[data-cy=modal-add-cta]').click()

    cy.wait(1000)

    // mark the status of the question as active
    cy.get('[data-cy=question-activate-link-1]').click()
    cy.get('[data-cy=question-activate-link-confirm-1]').click()

    // then, answer the question on the dashboard
    cy.visit('/1/dashboard/me')
    cy.get('[data-cy=log-answer-cta]').click()
    cy.get('[data-cy=answer-content]').type('this is my answer')
    cy.get('[data-cy=submit-answer]').click()

    cy.changePermission(1, 300)

    // then, go to the employee page
    cy.visit('/1/employees/1')
    cy.get('[data-cy=question-blank-state]').should('not.exist')
    cy.get('[data-cy=question-title-1]').contains('this is my question 1')
    cy.get('[data-cy=answer-body-1]').contains('this is my answer')
  })
})
