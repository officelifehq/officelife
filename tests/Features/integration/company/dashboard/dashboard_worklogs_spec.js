describe('Worklogs - employee', function () {
  it('should let the employee logs a worklog', function () {
    cy.login()

    cy.createCompany()

    // find the worklog tab, enter the text and press save
    cy.get('[data-cy=log-worklog-cta]').click()
    cy.get('[data-cy=worklog-content]').find('[contenteditable]').type('I did two things today')
    cy.get('[data-cy=submit-log-worklog]').click()

    cy.contains('You rock')

    // assign a team to the employee
    cy.createTeam('product')

    cy.visit('/1/employees/1')

    cy.get('[data-cy=open-team-modal-blank]').click()
    cy.get('[data-cy=list-team-1]').click()

    cy.visit('/1/dashboard')

    cy.get('[data-cy=dashboard-team-tab]').click()

    cy.get('body').should('contain', 'What your team has done this week')
    cy.contains('I did two things today')
  })
})
