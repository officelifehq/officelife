describe('Adminland - Team management', function () {
  it('should create a team', function () {
    cy.login()

    cy.createCompany()

    cy.createTeam('product')

    cy.contains('product')
  })
})
