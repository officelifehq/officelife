describe('Adminland - Questions', function () {
  it('should let you manage company questions as an administrator', function () {
    cy.login()

    cy.createCompany()

    cy.visit('/1/account')
    cy.get('[data-cy=questions-admin-link]').click()

    // blank state should exist
    cy.get('[data-cy=questions-blank-message]').should('exist')

    //add two questions
    cy.get('[data-cy=add-question-button]').click()
    cy.get('[data-cy=add-title-input]').type('this is my question 1')
    cy.get('[data-cy=modal-add-cta]').click()

    cy.wait(1000)

    cy.get('[data-cy=add-question-button]').click()
    cy.get('[data-cy=add-title-input]').type('this is my question 2')
    cy.get('[data-cy=modal-add-cta]').click()

    cy.hasAuditLog('Added a question called', '/1/account/questions')

    //renaming the first question
    cy.get('[data-cy=question-rename-link-1]').click()
    cy.get('[data-cy=list-rename-input-name-1]').clear()
    cy.get('[data-cy=list-rename-input-name-1]').type('updated text')
    cy.get('[data-cy=list-rename-cta-button-1]').click()

    cy.hasAuditLog('Updated the question', '/1/account/questions')

    // deleting the first question
    cy.get('[data-cy=question-destroy-link-1]').click()
    cy.get('[data-cy=list-destroy-cta-button-1]').click()

    cy.hasAuditLog('Deleted the question called', '/1/account/questions')

    // mark the status of the second question as active
    cy.get('[data-cy=question-activate-link-2]').click()
    cy.get('[data-cy=question-activate-link-confirm-2]').click()
    cy.get('[data-cy=question-status-active-2]').should('exist')
    cy.get('[data-cy=question-status-inactive-2]').should('not.exist')

    cy.hasAuditLog('Enabled the question', '/1/account/questions')

    // disable the question now
    cy.get('[data-cy=question-deactivate-link-2]').click()
    cy.get('[data-cy=question-deactivate-link-confirm-2]').click()
    cy.get('[data-cy=question-status-active-2]').should('not.exist')
    cy.get('[data-cy=question-status-inactive-2]').should('exist')

    // check that the number of answers is 0
    cy.get('[data-cy=question-number-of-answers-2]').contains('0 answer')

    cy.hasAuditLog('Disabled the question called', '/1/account/questions')
  })

  it('should let you manage company questions as an HR', function () {
    cy.login()

    cy.createCompany()

    cy.changePermission(1, 200)
    cy.visit('/1/account')
    cy.get('[data-cy=questions-admin-link]').click()

    // blank state should exist
    cy.get('[data-cy=questions-blank-message]').should('exist')

    //add two questions
    cy.get('[data-cy=add-question-button]').click()
    cy.get('[data-cy=add-title-input]').type('this is my question 1')
    cy.get('[data-cy=modal-add-cta]').click()
    cy.wait(1000)
    cy.get('[data-cy=add-question-button]').click()
    cy.get('[data-cy=add-title-input]').type('this is my question 2')
    cy.get('[data-cy=modal-add-cta]').click()

    //renaming the first question
    cy.get('[data-cy=question-rename-link-1]').click()
    cy.get('[data-cy=list-rename-input-name-1]').clear()
    cy.get('[data-cy=list-rename-input-name-1]').type('updated text')
    cy.get('[data-cy=list-rename-cta-button-1]').click()

    // deleting the first question
    cy.get('[data-cy=question-destroy-link-1]').click()
    cy.get('[data-cy=list-destroy-cta-button-1]').click()

    // mark the status of the second question as active
    cy.get('[data-cy=question-activate-link-2]').click()
    cy.get('[data-cy=question-activate-link-confirm-2]').click()
    cy.get('[data-cy=question-status-active-2]').should('exist')
    cy.get('[data-cy=question-status-inactive-2]').should('not.exist')

    // disable the question now
    cy.get('[data-cy=question-deactivate-link-2]').click()
    cy.get('[data-cy=question-deactivate-link-confirm-2]').click()
    cy.get('[data-cy=question-status-active-2]').should('not.exist')
    cy.get('[data-cy=question-status-inactive-2]').should('exist')

    // check that the number of answers is 0
    cy.get('[data-cy=question-number-of-answers-2]').contains('0 answer')
  })
})
