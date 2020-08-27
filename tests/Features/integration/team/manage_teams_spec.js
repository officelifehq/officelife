describe('Teams - Manage teams', function () {
  it('should display a blank state', function () {
    cy.login()

    cy.createCompany()

    // access the team link in the header
    cy.visit('/1/dashboard/me')
    cy.get('[data-cy=dashboard-team-tab]').click()
    cy.contains('You are not associated with a team at the moment.')
  })

  it('should display the team in a list', function () {
    cy.login()

    cy.createCompany()

    cy.createTeam('product')
    cy.wait(1000)

    cy.assignEmployeeToTeam(1, 1)

    cy.visit('/1/dashboard/me')
    // access the team link in the header
    cy.get('[data-cy=dashboard-team-tab]').click()
  })
})
