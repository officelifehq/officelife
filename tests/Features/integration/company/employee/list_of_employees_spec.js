describe('Employee - Assign positions', function () {
  it('should assign a position and remove it as administrator', function () {
    cy.login()

    cy.createCompany()

    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin')
    cy.createEmployee('Dwight', 'Schrute', 'dwight.schrute@dundermifflin.com', 'hr')
    cy.createEmployee('Jim', 'Halpert', 'jim.halpert@dundermifflin.com', 'user')

    cy.visit('/1/employees')

    cy.contains('Michael Scott')
    cy.contains('Dwight Schrute')
    cy.contains('Jim Halpert')
  })
})
