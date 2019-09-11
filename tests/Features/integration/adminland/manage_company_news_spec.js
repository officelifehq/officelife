describe('Adminland - Company news management', function () {
  it('should let user access company news adminland screen with the right permissions', function () {
    cy.login()

    cy.createCompany()

    cy.canAccess('/1/account/news', 100, 'Company news')
    cy.canAccess('/1/account/news', 200, 'Company news')
    cy.canNotAccess('/1/account/news', 300)
  })

  it('should let you manage company news as an administrator', function () {
    cy.login()

    cy.createCompany()

    cy.visit('/1/account')
    cy.get('[data-cy=news-admin-link]').click()

    // open the new page
    cy.get('[data-cy=add-news-button]').click()

    // start to populate it and press save
    cy.get('[data-cy=news-title-input').type('News of the week')
    cy.get('[data-cy=news-content-textarea').type('Dwight is now officially the new assistant to the regional manager.')
    cy.get('[data-cy=submit-add-news-button]').click()

    // check to see if the data is there in the table
    cy.get('[data-cy=news-list]').contains('News of the week')
    cy.hasAuditLog('Wrote a company news called News of the week', '/1/account/news')

    // access the row we just created to rename it
    // '1' is the ID of the news we've created
    cy.get('[data-cy=edit-news-button-1]').click()
    cy.get('[data-cy=cancel-button]').click()
    cy.url().should('include', '/1/account/news')

    // go back to the news and edit it
    cy.get('[data-cy=edit-news-button-1]').click()
    cy.get('[data-cy=news-title-input').clear()
    cy.get('[data-cy=news-title-input').type('No news of the week')
    cy.get('[data-cy=news-content-textarea').clear()
    cy.get('[data-cy=news-content-textarea').type('Dwight is now officially the new assistant regional manager.')
    cy.get('[data-cy=submit-update-news-button]').click()
    cy.hasAuditLog('Updated the news called No news of the week.', '/1/account/news')

    cy.wait(2200)

    // delete the company news
    cy.get('[data-cy=list-delete-button-1]').click()
    cy.get('[data-cy=list-delete-cancel-button-1]').click()
    cy.get('[data-cy=list-delete-button-1]').click()
    cy.get('[data-cy=list-delete-confirm-button-1]').click()
    cy.get('[data-cy=news-list]').should('not.contain', 'No news of the week')
    cy.hasAuditLog('Destroyed the news called No news of the week', '/1/account/news')
  })

  it('should let you manage company news as an hr representative', function () {
    cy.login()

    cy.createCompany()

    cy.changePermission(1, 200)

    cy.visit('/1/account')
    cy.get('[data-cy=news-admin-link]').click()

    // open the new page
    cy.get('[data-cy=add-news-button]').click()

    // start to populate it and press save
    cy.get('[data-cy=news-title-input').type('News of the week')
    cy.get('[data-cy=news-content-textarea').type('Dwight is now officially the new assistant to the regional manager.')
    cy.get('[data-cy=submit-add-news-button]').click()

    // check to see if the data is there in the table
    cy.get('[data-cy=news-list]').contains('News of the week')

    // access the row we just created to rename it
    // '1' is the ID of the news we've created
    cy.get('[data-cy=edit-news-button-1]').click()
    cy.get('[data-cy=cancel-button]').click()
    cy.url().should('include', '/1/account/news')

    // go back to the news and edit it
    cy.get('[data-cy=edit-news-button-1]').click()
    cy.get('[data-cy=news-title-input').clear()
    cy.get('[data-cy=news-title-input').type('No news of the week')
    cy.get('[data-cy=news-content-textarea').clear()
    cy.get('[data-cy=news-content-textarea').type('Dwight is now officially the new assistant regional manager.')
    cy.get('[data-cy=submit-update-news-button]').click()

    cy.wait(2200)

    // delete the company news
    cy.get('[data-cy=list-delete-button-1]').click()
    cy.get('[data-cy=list-delete-cancel-button-1]').click()
    cy.get('[data-cy=list-delete-button-1]').click()
    cy.get('[data-cy=list-delete-confirm-button-1]').click()
    cy.get('[data-cy=news-list]').should('not.contain', 'No news of the week')
  })
})
