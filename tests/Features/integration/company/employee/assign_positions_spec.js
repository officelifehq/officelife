describe('Employee', function () {
  it.skip('should let ', function () {
    cy.login()

    cy.createCompany()

    cy.wait(500)

    cy.canAccess('/1/account/positions', 100, 'Positions')
    cy.canAccess('/1/account/positions', 200, 'Positions')
    cy.canNotAccess('/1/account/positions', 300)
  })
})
