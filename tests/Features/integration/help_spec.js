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
    cy.get('[data-cy=header-adminland-link]').click()
    cy.get('[data-cy=hardware-admin-link]').click()
    cy.get('[data-cy=add-hardware-button]').click()
    cy.get('[data-cy=help-icon-hardware]').click()

    // locking
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', false)
    cy.get('[data-cy=header-adminland-link]').click()
    cy.get('[data-cy=employee-admin-link]').click()

    cy.get('[data-cy=employees]').should('be.visible')
      .invoke('attr', 'cy-items').then(function (items) {

        let item = _.last(items.split(','));

        cy.get('[data-cy=lock-account-'+item+']').click()
        cy.get('[data-cy=help-icon-employee-lock]').click()
    });

    // deleting employee
    cy.get('[data-cy=header-adminland-link]').click()
    cy.get('[data-cy=employee-admin-link]').click()

    cy.visit('/1/account/employees/2/delete')
    cy.get('[data-cy=help-icon-employee-delete]').click()
  })
})
