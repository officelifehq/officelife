describe('Adminland - Employee management', function () {
  it('should create an employee with different permission levels', function () {
    cy.login()

    cy.createCompany()

    cy.wait(500)

    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin')
    cy.contains('Michael Scott')
    cy.hasAuditLog('Added Michael Scott as an employee', '/1/account/employees')

    cy.createEmployee('Dwight', 'Schrute', 'dwight.schrute@dundermifflin.com', 'hr')
    cy.contains('Dwight Schrute')
    cy.hasAuditLog('Added Dwight Schrute as an employee', '/1/account/employees')

    cy.createEmployee('Jim', 'Halpert', 'jim.halpert@dundermifflin.com', 'user')
    cy.contains('Jim Halpert')
    cy.hasAuditLog('Added Jim Halpert as an employee', '/1/account/employees')
  })
})
