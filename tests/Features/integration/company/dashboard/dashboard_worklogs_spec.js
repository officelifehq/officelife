describe('Worklogs - employee', function () {
  it('should let the employee logs a worklog', function () {
    cy.login()

    cy.createCompany()

    cy.get('[data-cy=log-worklog-cta]').click()

    cy.get('[data-cy=worklog-content]').type('I did two things today')

    cy.get('[data-cy=submit-log-worklog]').click()
  })
})
