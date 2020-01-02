describe('Team - Team news management', function () {
  it('should let user access team news regardless of the team they are part of', function () {
    cy.login()

    cy.createCompany()
    cy.createTeam('product')

    cy.canAccess('/1/teams/1/news', 100, 'news')
    cy.canAccess('/1/teams/1/news', 200, 'news')
    cy.canAccess('/1/teams/1/news', 300, 'news')
  })

  it('should let you manage team news as an administrator', function () {
    cy.login()

    cy.createCompany()
    cy.createTeam('product')

    cy.wait(1000)

    cy.visit('/1/teams/1')

    // open the new page
    cy.get('[data-cy=add-team-news]').click()

    // start to populate it and press save
    cy.get('[data-cy=news-title-input').type('News of the week')
    cy.get('[data-cy=news-content-textarea').type('Dwight is now officially the new assistant to the regional manager.')
    cy.get('[data-cy=submit-add-news-button]').click()

    // check to see if the data is there in the table
    cy.get('[data-cy=news-list]').contains('News of the week')
    cy.hasAuditLog('Wrote a news called News of the week for the team', '/1/teams/1')

    // go to the page showing all the news
    cy.get('[data-cy=view-all-news]').click()
    cy.get('[data-cy=edit-news-button-1]').click()
    cy.get('[data-cy=news-title-input').clear()
    cy.get('[data-cy=news-title-input').type('No news of the week')
    cy.get('[data-cy=news-content-textarea').clear()
    cy.get('[data-cy=news-content-textarea').type('Dwight is now officially the new assistant regional manager.')
    cy.get('[data-cy=submit-update-news-button]').click()
    cy.hasAuditLog('Updated the news called No news of the week', '/1/teams/1/news')

    cy.wait(1000)

    // delete the news
    cy.get('[data-cy=delete-news-button-1]').click()
    cy.get('[data-cy=delete-news-button-cancel-1]').click()
    cy.get('[data-cy=delete-news-button-1]').click()
    cy.get('[data-cy=delete-news-button-confirm-1]').click()
    cy.get('[data-cy=news-list]').should('not.contain', 'No news of the week')
    cy.hasAuditLog('Deleted the news called No news of the week', '/1/teams/1/news')
  })

  it('should let you manage team news as an HR', function () {
    cy.login()

    cy.createCompany()
    cy.createTeam('product')

    cy.wait(1000)

    cy.changePermission(1, 200)

    cy.visit('/1/teams/1')

    // open the new page
    cy.get('[data-cy=add-team-news]').click()

    // start to populate it and press save
    cy.get('[data-cy=news-title-input').type('News of the week')
    cy.get('[data-cy=news-content-textarea').type('Dwight is now officially the new assistant to the regional manager.')
    cy.get('[data-cy=submit-add-news-button]').click()

    // check to see if the data is there in the table
    cy.get('[data-cy=news-list]').contains('News of the week')

    // go to the page showing all the news
    cy.get('[data-cy=view-all-news]').click()
    cy.get('[data-cy=edit-news-button-1]').click()
    cy.get('[data-cy=news-title-input').clear()
    cy.get('[data-cy=news-title-input').type('No news of the week')
    cy.get('[data-cy=news-content-textarea').clear()
    cy.get('[data-cy=news-content-textarea').type('Dwight is now officially the new assistant regional manager.')
    cy.get('[data-cy=submit-update-news-button]').click()

    cy.wait(1000)

    // delete the news
    cy.get('[data-cy=delete-news-button-1]').click()
    cy.get('[data-cy=delete-news-button-cancel-1]').click()
    cy.get('[data-cy=delete-news-button-1]').click()
    cy.get('[data-cy=delete-news-button-confirm-1]').click()
    cy.get('[data-cy=news-list]').should('not.contain', 'No news of the week')
  })

  it('should let you manage team news as a normal user', function () {
    cy.login()

    cy.createCompany()
    cy.createTeam('product')

    cy.wait(1000)

    cy.changePermission(1, 200)

    cy.visit('/1/teams/1')

    // open the new page
    cy.get('[data-cy=add-team-news]').click()

    // start to populate it and press save
    cy.get('[data-cy=news-title-input').type('News of the week')
    cy.get('[data-cy=news-content-textarea').type('Dwight is now officially the new assistant to the regional manager.')
    cy.get('[data-cy=submit-add-news-button]').click()

    // check to see if the data is there in the table
    cy.get('[data-cy=news-list]').contains('News of the week')

    // go to the page showing all the news
    cy.get('[data-cy=view-all-news]').click()
    cy.get('[data-cy=edit-news-button-1]').click()
    cy.get('[data-cy=news-title-input').clear()
    cy.get('[data-cy=news-title-input').type('No news of the week')
    cy.get('[data-cy=news-content-textarea').clear()
    cy.get('[data-cy=news-content-textarea').type('Dwight is now officially the new assistant regional manager.')
    cy.get('[data-cy=submit-update-news-button]').click()

    cy.wait(1000)

    // delete the news
    cy.get('[data-cy=delete-news-button-1]').click()
    cy.get('[data-cy=delete-news-button-cancel-1]').click()
    cy.get('[data-cy=delete-news-button-1]').click()
    cy.get('[data-cy=delete-news-button-confirm-1]').click()
    cy.get('[data-cy=news-list]').should('not.contain', 'No news of the week')
  })
})
