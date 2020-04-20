describe('Employee - List of employees', function () {
  it('should let create employees and see it in a list', function () {
    cy.login()

    cy.createCompany()

    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', false)
    cy.createEmployee('Dwight', 'Schrute', 'dwight.schrute@dundermifflin.com', 'hr', false)
    cy.createEmployee('Jim', 'Halpert', 'jim.halpert@dundermifflin.com', 'user', false)

    cy.visit('/1/employees')

    cy.contains('Michael Scott')
    cy.contains('Dwight Schrute')
    cy.contains('Jim Halpert')
  })
})
