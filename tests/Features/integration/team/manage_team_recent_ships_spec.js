describe('Team - Recent ships management', function () {
  it('should let you manage recent ship as an administrator', function () {
    cy.login()

    cy.createCompany()
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', false)
    cy.createTeam('product')

    cy.wait(1000)

    cy.visit('/1/teams/1')
    cy.get('[data-cy=recent-ships-list-blank-state]').should('exist')

    // open the new page
    cy.get('[data-cy=add-recent-ship-entry]').click()

    // create an entry with only title
    cy.get('[data-cy=recent-ship-title-input').type('Feature 1 shipped')
    cy.get('[data-cy=submit-add-ship-button]').click()

    // check to see if the data is there in the table
    cy.get('[data-cy=ships-list-1]').contains('Feature 1 shipped')
    cy.hasAuditLog('Created a recent ship entry called Feature 1 shipped', '/1/teams/1')
    cy.hasTeamLog('Created a new recent ship called Feature 1 shipped', '/1/teams/1')

    // create an entry with title+description
    cy.get('[data-cy=add-recent-ship-entry]').click()
    cy.get('[data-cy=recent-ship-title-input').type('Feature 2 shipped')
    cy.get('[data-cy=ship-add-description]').click()
    cy.get('[data-cy=ship-description]').type('description')
    cy.get('[data-cy=submit-add-ship-button]').click()

    // check to see if the data is there in the table
    cy.get('[data-cy=ships-list-2]').contains('Feature 2 shipped')
    cy.hasAuditLog('Created a recent ship entry called Feature 2 shipped', '/1/teams/1')
    cy.hasTeamLog('Created a new recent ship called Feature 2 shipped', '/1/teams/1')

    // create an entry with title+description+employees
    cy.get('[data-cy=add-recent-ship-entry]').click()
    cy.get('[data-cy=recent-ship-title-input').type('Feature 3 shipped')
    cy.get('[data-cy=ship-add-description]').click()
    cy.get('[data-cy=ship-description]').type('description')
    cy.get('[data-cy=ship-add-employees]').click()
    cy.get('[data-cy=ship-employees]').type('Michael')
    cy.wait(600)
    cy.get('[data-cy=employee-id-2]').click()
    cy.get('[data-cy=submit-add-ship-button]').click()

    // check to see if the data is there in the table
    cy.get('[data-cy=ships-list-3]').contains('Feature 3 shipped')
    cy.get('[data-cy=ship-list-3-avatar-2]').should('exist')
    cy.hasAuditLog('Created a recent ship entry called Feature 3 shipped', '/1/teams/1')
    cy.hasTeamLog('Created a new recent ship called Feature 3 shipped', '/1/teams/1')
  })

  it('should let you manage recent ship as an HR', function () {
    cy.login()

    cy.createCompany()
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', false)
    cy.createTeam('product')

    cy.changePermission(1, 200)

    cy.wait(1000)

    cy.visit('/1/teams/1')
    cy.get('[data-cy=recent-ships-list-blank-state]').should('exist')

    // open the new page
    cy.get('[data-cy=add-recent-ship-entry]').click()

    // create an entry with only title
    cy.get('[data-cy=recent-ship-title-input').type('Feature 1 shipped')
    cy.get('[data-cy=submit-add-ship-button]').click()

    // check to see if the data is there in the table
    cy.get('[data-cy=ships-list-1]').contains('Feature 1 shipped')

    // create an entry with title+description
    cy.get('[data-cy=add-recent-ship-entry]').click()
    cy.get('[data-cy=recent-ship-title-input').type('Feature 2 shipped')
    cy.get('[data-cy=ship-add-description]').click()
    cy.get('[data-cy=ship-description]').type('description')
    cy.get('[data-cy=submit-add-ship-button]').click()

    // check to see if the data is there in the table
    cy.get('[data-cy=ships-list-2]').contains('Feature 2 shipped')

    // create an entry with title+description+employees
    cy.get('[data-cy=add-recent-ship-entry]').click()
    cy.get('[data-cy=recent-ship-title-input').type('Feature 3 shipped')
    cy.get('[data-cy=ship-add-description]').click()
    cy.get('[data-cy=ship-description]').type('description')
    cy.get('[data-cy=ship-add-employees]').click()
    cy.get('[data-cy=ship-employees]').type('Michael')
    cy.wait(600)
    cy.get('[data-cy=employee-id-2]').click()
    cy.get('[data-cy=submit-add-ship-button]').click()

    // check to see if the data is there in the table
    cy.get('[data-cy=ships-list-3]').contains('Feature 3 shipped')
    cy.get('[data-cy=ship-list-3-avatar-2]').should('exist')
  })

  it('should let you manage recent ship as a normal user who is part of the team', function () {
    cy.login()

    cy.createCompany()
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', false)
    cy.createTeam('product')

    // add admin to the team so he can manage the team
    cy.visit('/1/teams/1')
    cy.get('[data-cy=manage-team-on]').click()
    cy.get('[data-cy=member-input]').type('admin@admin.com')
    cy.wait(600)
    cy.get('[data-cy=employee-id-1]').click()
    cy.visit('/1/teams/1')

    cy.changePermission(1, 300)

    cy.wait(1000)

    cy.visit('/1/teams/1')
    cy.get('[data-cy=recent-ships-list-blank-state]').should('exist')

    // open the new page
    cy.get('[data-cy=add-recent-ship-entry]').click()

    // create an entry with only title
    cy.get('[data-cy=recent-ship-title-input').type('Feature 1 shipped')
    cy.get('[data-cy=submit-add-ship-button]').click()

    // check to see if the data is there in the table
    cy.get('[data-cy=ships-list-1]').contains('Feature 1 shipped')

    // create an entry with title+description
    cy.get('[data-cy=add-recent-ship-entry]').click()
    cy.get('[data-cy=recent-ship-title-input').type('Feature 2 shipped')
    cy.get('[data-cy=ship-add-description]').click()
    cy.get('[data-cy=ship-description]').type('description')
    cy.get('[data-cy=submit-add-ship-button]').click()

    // check to see if the data is there in the table
    cy.get('[data-cy=ships-list-2]').contains('Feature 2 shipped')

    // create an entry with title+description+employees
    cy.get('[data-cy=add-recent-ship-entry]').click()
    cy.get('[data-cy=recent-ship-title-input').type('Feature 3 shipped')
    cy.get('[data-cy=ship-add-description]').click()
    cy.get('[data-cy=ship-description]').type('description')
    cy.get('[data-cy=ship-add-employees]').click()
    cy.get('[data-cy=ship-employees]').type('Michael')
    cy.wait(600)
    cy.get('[data-cy=employee-id-2]').click()
    cy.get('[data-cy=submit-add-ship-button]').click()

    // check to see if the data is there in the table
    cy.get('[data-cy=ships-list-3]').contains('Feature 3 shipped')
    cy.get('[data-cy=ship-list-3-avatar-2]').should('exist')
  })

  it('should not let you manage recent ships as a normal user who is not part of the team', function () {
    cy.login()

    cy.createCompany()
    cy.createTeam('product')

    cy.wait(1000)

    cy.changePermission(1, 300)

    cy.visit('/1/teams/1')

    // open the new page
    cy.get('[data-cy=add-recent-ship-entry]').should('not.exist')
  })
})
