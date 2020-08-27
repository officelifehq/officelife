describe('Dashboard - employee - log morale', function () {
  it('should let the employee logs a bad morale', function () {
    cy.login()

    cy.createCompany()

    // press the bad emotion button
    cy.visit('/1/dashboard/me')
    cy.get('[data-cy=log-morale-bad]').click()

    cy.contains('Thanks for telling us how you feel')
    cy.wait(500)

    // visiting the page again should display another message
    cy.hasAuditLog('Added an emotion on how the day went', '/1/employees/1')
    cy.hasEmployeeLog('Added an emotion on how the day went', '/1/employees/1')

    cy.visit('/1/dashboard/me')
    cy.contains('You have already indicated how you felt today. Come back tomorrow!')
    cy.get('body').should('not.contain', 'Best day ever')
  })

  it('should let the employee logs a normal morale', function () {
    cy.login()

    cy.createCompany()

    // press the bad emotion button
    cy.visit('/1/dashboard/me')
    cy.get('[data-cy=log-morale-normal]').click()

    cy.contains('Thanks for telling us how you feel')
    cy.wait(500)

    // visiting the page again should display another message
    cy.hasAuditLog('Added an emotion on how the day went', '/1/employees/1')
    cy.hasEmployeeLog('Added an emotion on how the day went', '/1/employees/1')
    cy.visit('/1/dashboard/me')
    cy.contains('You have already indicated how you felt today. Come back tomorrow!')
    cy.get('body').should('not.contain', 'Best day ever')
  })

  it('should let the employee logs a good morale', function () {
    cy.login()

    cy.createCompany()

    // press the bad emotion button
    cy.visit('/1/dashboard/me')
    cy.get('[data-cy=log-morale-good]').click()

    cy.contains('Thanks for telling us how you feel')
    cy.wait(500)

    // visiting the page again should display another message
    cy.hasAuditLog('Added an emotion on how the day went', '/1/employees/1')
    cy.hasEmployeeLog('Added an emotion on how the day went', '/1/employees/1')
    cy.visit('/1/dashboard/me')
    cy.contains('You have already indicated how you felt today. Come back tomorrow!')
    cy.get('body').should('not.contain', 'Best day ever')
  })
})
