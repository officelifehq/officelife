describe('Adminland - Positions management', function () {
  it('should let user access position adminland screen with the right permissions', function () {
    cy.login()

    cy.createCompany()

    cy.canAccess('/1/account/positions', 100, 'Positions')
    cy.canAccess('/1/account/positions', 200, 'Positions')
    cy.canNotAccess('/1/account/positions', 300)
  })

  it('should populate an account with default positions', function () {
    cy.login()

    cy.createCompany()

    cy.visit('/1/account')
    cy.get('[data-cy=position-admin-link]').click()
    cy.contains('CEO')
    cy.contains('Front end developer')
  })

  it('should let you manage positions as an administrator', function () {
    cy.login()

    cy.createCompany()

    cy.visit('/1/account')
    cy.get('[data-cy=position-admin-link]').click()

    // open the popup
    cy.get('[data-cy=add-position-button]').click()

    // start to populate it and press save
    cy.get('[data-cy=add-title-input]').type('Assistant to the regional manager')
    cy.get('[data-cy=modal-add-cta]').click()

    // check to see if the data is there in the table
    cy.get('[data-cy=positions-list]').contains('Assistant to the regional manager')
    cy.hasAuditLog('Created a position called Assistant to the regional manager', '/1/account/positions')

    // access the row we just created to rename it
    // '5' is the ID of the position we've created, as an account is populated
    // with 4 titles
    cy.get('[data-cy=list-rename-button-5]').click()
    cy.get('[data-cy=list-rename-cancel-button-5]').click()
    cy.get('[data-cy=list-rename-button-5]').click()
    cy.get('[data-cy=list-rename-input-name-5]').clear()
    cy.get('[data-cy=list-rename-input-name-5]').type('Assistant regional manager')
    cy.get('[data-cy=list-rename-cta-button-5]').click()
    cy.get('[data-cy=positions-list]').contains('Assistant regional manager')
    cy.hasAuditLog('Updated the position formely called Assistant to the regional manager to Assistant regional manager', '/1/account/positions')

    // delete the position
    cy.get('[data-cy=list-delete-button-5]').click()
    cy.get('[data-cy=list-delete-cancel-button-5]').click()
    cy.get('[data-cy=list-delete-button-5]').click()
    cy.get('[data-cy=list-delete-confirm-button-5]').click()
    cy.get('[data-cy=positions-list]').should('not.contain', 'Assistant regional manager')
    cy.hasAuditLog('Destroyed the position called Assistant regional manager', '/1/account/positions')

    cy.contains('CEO')
    cy.contains('Front end developer')
  })

  it('should let you manage positions as an HR', function () {
    cy.login()

    cy.createCompany()

    cy.changePermission(1, 200)
    cy.visit('/1/account')
    cy.get('[data-cy=position-admin-link]').click()

    // open the popup
    cy.get('[data-cy=add-position-button]').click()

    // start to populate it and press save
    cy.get('[data-cy=add-title-input]').type('Assistant to the regional manager')
    cy.get('[data-cy=modal-add-cta]').click()

    // check to see if the data is there in the table
    cy.get('[data-cy=positions-list]').contains('Assistant to the regional manager')

    // access the row we just created to rename it
    // '5' is the ID of the position we've created, as an account is populated
    // with 4 titles
    cy.get('[data-cy=list-rename-button-5]').click()
    cy.get('[data-cy=list-rename-cancel-button-5]').click()
    cy.get('[data-cy=list-rename-button-5]').click()
    cy.get('[data-cy=list-rename-input-name-5]').clear()
    cy.get('[data-cy=list-rename-input-name-5]').type('Assistant regional manager')
    cy.get('[data-cy=list-rename-cta-button-5]').click()
    cy.get('[data-cy=positions-list]').contains('Assistant regional manager')

    // delete the position
    cy.get('[data-cy=list-delete-button-5]').click()
    cy.get('[data-cy=list-delete-cancel-button-5]').click()
    cy.get('[data-cy=list-delete-button-5]').click()
    cy.get('[data-cy=list-delete-confirm-button-5]').click()
    cy.get('[data-cy=positions-list]').should('not.contain', 'Assistant regional manager')

    cy.contains('CEO')
    cy.contains('Front end developer')
  })
})
