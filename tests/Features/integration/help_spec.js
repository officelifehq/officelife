describe('Help - Show and validate help for all features', function () {
  it('should check that all links about help are valid', function () {
    cy.login()

    cy.createCompany()

    cy.wait(2000)

    // make sure the toggle feature exists
    cy.get('[data-cy=layout-show-help]').click()
    cy.wait(200)
    cy.get('[data-cy=layout-show-help]').should('not.exist')
    cy.get('[data-cy=layout-hide-help]').click()
    cy.wait(200)
    cy.get('[data-cy=layout-hide-help]').should('not.exist')
    cy.get('[data-cy=layout-show-help]').click()


    /*–----------------------
    * DASHBOARD
    */
    // work from home
    cy.get('[data-cy=help-icon-work-from-home]').should('exist')
    cy.get('[data-cy=help-icon-work-from-home]').click()


    /*–----------------------
    * ADMINLAND
    */
    // hardware
    cy.visit('/1/account/hardware/create')
    cy.get('[data-cy=help-icon-hardware]').click()

    // locking
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', false)
    cy.visit('/1/account/employees/2/lock')
    cy.get('[data-cy=help-icon-employee-lock]').click()

    // deleting employee
    cy.visit('/1/account/employees/2/delete')
    cy.get('[data-cy=help-icon-employee-delete]').click()
  })
})
