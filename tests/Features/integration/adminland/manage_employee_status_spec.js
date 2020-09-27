describe('Adminland - Employee statuses', function () {
  it('should let user access employee statuses adminland screen with the right permissions', function () {
    cy.loginLegacy()

    cy.createCompany()

    cy.get('[data-cy=header-adminland-link]')
      .invoke('attr', 'href').then(function (href) {

      cy.canAccess(href+'/employeestatuses', 100, 'All the employee statuses in ')
      cy.canAccess(href+'/employeestatuses', 200, 'All the employee statuses in ')
      cy.canNotAccess(href+'/employeestatuses', 300)
    })
  })

  it('should let you manage employee statuses as an administrator', function () {
    cy.login((userId) => {

      cy.createCompany()

      cy.get('[data-cy=header-adminland-link]').click()
      cy.get('[data-cy=employee-statuses-admin-link]').click()

      // open the popup
      cy.get('[data-cy=add-status-button]').click()

      // start to populate it and press save
      cy.get('[data-cy=add-title-input]').type('Full-time')
      cy.get('[data-cy=modal-add-cta]').click()

      // check to see if the data is there in the table
      cy.get('[data-cy=statuses-list]').contains('Full-time')
      cy.hasAuditLog('Added an employee status called Full-time', '/'+userId+'/account/employeestatuses')

      // access the row we just created to rename it
      cy.get('[data-cy=list-rename-button-1]').click()
      cy.get('[data-cy=list-rename-cancel-button-1]').click()
      cy.get('[data-cy=list-rename-button-1]').click()
      cy.get('[data-cy=list-rename-input-name-1]').clear()
      cy.get('[data-cy=list-rename-input-name-1]').type('Part-time')
      cy.get('[data-cy=list-rename-cta-button-1]').click()
      cy.get('[data-cy=statuses-list]').contains('Part-time')
      cy.hasAuditLog('Updated the name of the employee status from Full-time to Part-time', '/'+userId+'/account/employeestatuses')

      // delete the position
      cy.get('[data-cy=list-delete-button-1]').click()
      cy.get('[data-cy=list-delete-cancel-button-1]').click()
      cy.get('[data-cy=list-delete-button-1]').click()
      cy.get('[data-cy=list-delete-confirm-button-1]').click()
      cy.get('[data-cy=positions-list]').should('not.contain', 'Part-time')
      cy.hasAuditLog('Destroyed the employee status called Part-time', '/'+userId+'/account/employeestatuses')
    })
  })

  it('should let you manage employee statuses as an HR', function () {
    cy.login((userId) => {

      cy.createCompany()

      cy.changePermission(userId, 200)
      cy.get('[data-cy=header-adminland-link]').click()
      cy.get('[data-cy=employee-statuses-admin-link]').click()

      // open the popup
      cy.get('[data-cy=add-status-button]').click()

      // start to populate it and press save
      cy.get('[data-cy=add-title-input]').type('Full-time')
      cy.get('[data-cy=modal-add-cta]').click()

      // check to see if the data is there in the table
      cy.get('[data-cy=statuses-list]').contains('Full-time')

      // access the row we just created to rename it
      cy.get('[data-cy=list-rename-button-1]').click()
      cy.get('[data-cy=list-rename-cancel-button-1]').click()
      cy.get('[data-cy=list-rename-button-1]').click()
      cy.get('[data-cy=list-rename-input-name-1]').clear()
      cy.get('[data-cy=list-rename-input-name-1]').type('Part-time')
      cy.get('[data-cy=list-rename-cta-button-1]').click()
      cy.get('[data-cy=statuses-list]').contains('Part-time')

      // delete the position
      cy.get('[data-cy=list-delete-button-1]').click()
      cy.get('[data-cy=list-delete-cancel-button-1]').click()
      cy.get('[data-cy=list-delete-button-1]').click()
      cy.get('[data-cy=list-delete-confirm-button-1]').click()
      cy.get('[data-cy=positions-list]').should('not.contain', 'Part-time')
    })
  })
})
