describe('Adminland', function () {
  it('should populate an account with default positions', function () {
    cy.login()

    cy.createCompany()

    cy.wait(500)

    cy.visit('/1/account')
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin')
    cy.contains('Michael Scott')

    cy.createEmployee('Dwight', 'Schrute', 'dwight.schrute@dundermifflin.com', 'hr')
    cy.contains('Dwight Schrute')

    cy.createEmployee('Jim', 'Halpert', 'jim.halpert@dundermifflin.com', 'user')
    cy.contains('Jim Halpert')
  })
})
