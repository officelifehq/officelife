describe('Company', function () {
  it('should create a team', function () {
    cy.login()

    cy.createCompany()

    cy.wait(500)

    cy.createTeam('product')

    cy.contains('product')
  })
})
