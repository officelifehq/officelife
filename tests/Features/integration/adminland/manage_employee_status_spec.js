describe('Adminland - Employee statuses', function () {
  it('should let user access employee statuses adminland screen with the right permissions', function () {
    cy.login()

    cy.createCompany()

    cy.canAccess('/1/account/employeestatuses', 100, 'All the employee statuses in Dunder Mifflin')
    cy.canAccess('/1/account/employeestatuses', 200, 'All the employee statuses in Dunder Mifflin')
    cy.canNotAccess('/1/account/employeestatuses', 300)
  })

  it('should let you manage employee statuses as an administrator', function () {
    cy.login()

    cy.createCompany()

    cy.visit('/1/account')
    cy.get('[data-cy=employee-statuses-admin-link]').click()

    // open the popup
    cy.get('[data-cy=add-status-button]').click()

    // start to populate it and press save
    cy.get('[data-cy=add-title-input]').type('Full-time')
    cy.get('[data-cy=modal-add-cta]').click()

    // check to see if the data is there in the table
    cy.get('[data-cy=statuses-list]').contains('Full-time')
    cy.hasAuditLog('Added an employee status called Full-time', '/1/account/employeestatuses')

    // access the row we just created to rename it
    cy.get('[data-cy=list-rename-button-1]').click()
    cy.get('[data-cy=list-rename-cancel-button-1]').click()
    cy.get('[data-cy=list-rename-button-1]').click()
    cy.get('[data-cy=list-rename-input-name-1]').clear()
    cy.get('[data-cy=list-rename-input-name-1]').type('Part-time')
    cy.get('[data-cy=list-rename-cta-button-1]').click()
    cy.get('[data-cy=statuses-list]').contains('Part-time')
    cy.hasAuditLog('Changed the name of the employee status from Full-time to Part-time', '/1/account/employeestatuses')

    // delete the position
    cy.get('[data-cy=list-delete-button-1]').click()
    cy.get('[data-cy=list-delete-cancel-button-1]').click()
    cy.get('[data-cy=list-delete-button-1]').click()
    cy.get('[data-cy=list-delete-confirm-button-1]').click()
    cy.get('[data-cy=positions-list]').should('not.contain', 'Part-time')
    cy.hasAuditLog('Destroyed the employee status called Part-time', '/1/account/employeestatuses')
  })

  it('should let you manage employee statuses as an HR', function () {
    cy.login()

    cy.createCompany()

    cy.changePermission(1, 200)
    cy.visit('/1/account')
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
