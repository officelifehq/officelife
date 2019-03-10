describe('Company', function () {
  it('should create an employee', function () {
    cy.login()

    cy.createCompany()

    cy.wait(500)

    cy.createEmployee()

    cy.contains('Michael Scott')
  })
})
