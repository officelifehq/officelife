const { company } = require("faker")

describe('Adminland - Employee statuses', function () {
  it('should let user access employee statuses adminland screen with the right permissions', function () {
    cy.login((userId) => {

      cy.createCompany()

      cy.get('[data-cy=header-adminland-link]').click()
      cy.get('[data-cy=employee-statuses-admin-link]', { timeout: 600 }).should('be.visible')
        .invoke('attr', 'href').then(function (url) {

        cy.canAccess(url, 100, 'All the employee statuses in ', userId)
        cy.canAccess(url, 200, 'All the employee statuses in ', userId)
        cy.canNotAccess(url, 300, userId)
      })
    })
  })

  it('should let you manage employee statuses as an administrator', function () {
    cy.login((userId) => {

      cy.createCompany((companyId) => {

        cy.get('[data-cy=header-adminland-link]').click()
        cy.get('[data-cy=employee-statuses-admin-link]').click()

        // open the popup
        cy.get('[data-cy=add-status-button]').click()

        // start to populate it and press save
        cy.get('[data-cy=add-title-input]').type('Full-time')
        cy.get('[data-cy=modal-add-cta]').click()

        // check to see if the data is there in the table
        cy.get('[data-cy=statuses-list]', {timeout: 500}).as('statuses-list').should('be.visible').contains('Full-time')
        cy.hasAuditLog('Added an employee status called Full-time', '/'+companyId+'/account/employeestatuses', companyId)

        cy.get('@statuses-list', {timeout: 500})
        .invoke('attr', 'data-cy-items').then(function (items) {
          let id = _.last(items.split(','));

          // access the row we just created to rename it
          cy.get('[data-cy=list-rename-button-'+id+']').click()
          cy.get('[data-cy=list-rename-cancel-button-'+id+']').click()
          cy.get('[data-cy=list-rename-button-'+id+']').click()
          cy.get('[data-cy=list-rename-input-name-'+id+']').clear()
          cy.get('[data-cy=list-rename-input-name-'+id+']').type('Part-time')
          cy.get('[data-cy=list-rename-cta-button-'+id+']').click()
          cy.get('[data-cy=statuses-list]').contains('Part-time')
          cy.hasAuditLog('Updated the name of the employee status from Full-time to Part-time', '/'+companyId+'/account/employeestatuses', companyId)

          // delete the position
          cy.get('[data-cy=list-delete-button-'+id+']').click()
          cy.get('[data-cy=list-delete-cancel-button-'+id+']').click()
          cy.get('[data-cy=list-delete-button-'+id+']').click()
          cy.get('[data-cy=list-delete-confirm-button-'+id+']').click()
          cy.get('[data-cy=positions-list]').should('not.contain', 'Part-time')
          cy.hasAuditLog('Destroyed the employee status called Part-time', null, companyId)
        })
      })
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
      cy.get('[data-cy=statuses-list]', {timeout: 500}).as('statuses-list').should('be.visible').contains('Full-time')

      cy.get('@statuses-list', {timeout: 500})
      .invoke('attr', 'data-cy-items').then(function (items) {
        let id = _.last(items.split(','));

        // access the row we just created to rename it
        cy.get('[data-cy=list-rename-button-'+id+']').click()
        cy.get('[data-cy=list-rename-cancel-button-'+id+']').click()
        cy.get('[data-cy=list-rename-button-'+id+']').click()
        cy.get('[data-cy=list-rename-input-name-'+id+']').clear()
        cy.get('[data-cy=list-rename-input-name-'+id+']').type('Part-time')
        cy.get('[data-cy=list-rename-cta-button-'+id+']').click()
        cy.get('[data-cy=statuses-list]').contains('Part-time')

        // delete the position
        cy.get('[data-cy=list-delete-button-'+id+']').click()
        cy.get('[data-cy=list-delete-cancel-button-'+id+']').click()
        cy.get('[data-cy=list-delete-button-'+id+']').click()
        cy.get('[data-cy=list-delete-confirm-button-'+id+']').click()
        cy.get('[data-cy=positions-list]').should('not.contain', 'Part-time')
      })
    })
  })
})
