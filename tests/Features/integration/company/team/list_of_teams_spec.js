describe('Teams - List of teams', function () {
  it('should display a blank list', function () {
    cy.login()

    cy.createCompany()

    // access the team link in the header
    cy.get('[data-cy=header-teams-link').click()

    // it should see the teams page
    cy.url().should('include', '/1/teams')
    cy.contains('Teams are a great way')
  })

  it('should display the team in a list', function () {
    cy.login()

    cy.createCompany()

    cy.createTeam('product')
    cy.assignEmployeeToTeam()

    // access the team link in the header
    cy.get('[data-cy=header-teams-link').click()

    // it should see the teams page
    cy.url().should('include', '/1/teams')
    cy.contains('product')
  })
})
