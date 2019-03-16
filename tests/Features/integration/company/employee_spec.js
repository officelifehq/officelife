describe('Company', function () {
  it('should create an employee', function () {
    cy.login()

    cy.createCompany()

    cy.wait(500)

    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com')

    cy.contains('Michael Scott')
  })

  it('should assign a manager and a direct report', function () {
    cy.login()

    cy.createCompany()

    cy.wait(500)

    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com')
    cy.createEmployee('Dwight', 'Schrute', 'dwight.schrute@dundermifflin.com')
    cy.createEmployee('Jim', 'Halpert', 'jim.halpert@dundermifflin.com')
    cy.get('[data-cy=employee-view]').first().click()

    // i should be now on Jim Halpert page so I will test that I can add
    // michael scott as Jim's manager
    cy.get('[data-cy=add-hierarchy-button]').click()
    cy.get('[data-cy=add-manager-button]').click()
    cy.get('[data-cy=search-manager]').type('scott')
    cy.get('[data-cy=potential-manager-button').click()
    cy.get('[data-cy=list-managers]').contains('Michael Scott')

    // I will now test that I can add dwight schrute as Jim's direct report
    cy.get('[data-cy=add-hierarchy-button]').click()
    cy.get('[data-cy=add-direct-report-button]').click()
    cy.get('[data-cy=search-direct-report]').type('dwight')
    cy.get('[data-cy=potential-direct-report-button').click()
    cy.get('[data-cy=list-direct-reports]').contains('Dwight Schrute')
  })
})
