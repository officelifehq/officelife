describe('Team - Team lead management', function () {
  it('should let you add a team lead who was not part of the team as an administrator', function () {
    cy.login()

    cy.createCompany()
    cy.createTeam('product')

    cy.createEmployee('Jim', 'Halpert', 'jim.halpert@dundermifflin.com', 'user', false)

    cy.wait(1000)

    cy.visit('/1/teams/1')

    cy.get('[data-cy=add-team-lead-blank-state]').click()

    // start to populate it and press save
    cy.get('[data-cy=search-team-lead]').type('jim')
    cy.get('[data-cy=potential-team-lead-2]').click()

    cy.get('[data-cy=current-team-lead]').contains('Jim Halpert')
    cy.hasAuditLog('Assigned Jim Halpert as the team leader of the team called product', '/1/teams/1')

    // also, the employee should be a member of the team now
    cy.get('[data-cy=members-list]').contains('Jim Halpert')

    // now remove the team lead
    cy.get('[data-cy=display-remove-team-lead-modal]').click()
    cy.get('[data-cy=remove-team-lead-button]').click()
    cy.get('[data-cy=confirm-remove-team-lead]').click()

    cy.get('[data-cy=team-lead-undefined]').should('not.exist')
    cy.hasAuditLog('Removed Jim Halpert as the team lead of the team called product', '/1/teams/1')
  })

  it('should let you add a team lead who was not part of the team as an hr', function () {
    cy.login()

    cy.createCompany()
    cy.createTeam('product')

    cy.createEmployee('Jim', 'Halpert', 'jim.halpert@dundermifflin.com', 'user', false)

    cy.wait(1000)

    cy.changePermission(1, 200)

    cy.visit('/1/teams/1')

    cy.get('[data-cy=add-team-lead-blank-state]').click()

    // start to populate it and press save
    cy.get('[data-cy=search-team-lead]').type('jim')
    cy.get('[data-cy=potential-team-lead-2]').click()

    cy.get('[data-cy=current-team-lead]').contains('Jim Halpert')

    // also, the employee should be a member of the team now
    cy.get('[data-cy=members-list]').contains('Jim Halpert')

    // now remove the team lead
    cy.get('[data-cy=display-remove-team-lead-modal]').click()
    cy.get('[data-cy=remove-team-lead-button]').click()
    cy.get('[data-cy=confirm-remove-team-lead]').click()

    cy.get('[data-cy=team-lead-undefined]').should('not.exist')
  })

  it('should not let you add a team lead who was not part of the team as an employee', function () {
    cy.login()

    cy.createCompany()
    cy.createTeam('product')

    cy.wait(1000)

    cy.changePermission(1, 300)

    cy.visit('/1/teams/1')

    cy.get('[data-cy=add-team-lead-blank-state]').should('not.exist')
  })

  it('should not let you manage team leads as an employee', function () {
    cy.login()

    cy.createCompany()
    cy.createTeam('product')

    cy.createEmployee('Jim', 'Halpert', 'jim.halpert@dundermifflin.com', 'user', false)

    cy.wait(1000)

    cy.visit('/1/teams/1')

    cy.get('[data-cy=add-team-lead-blank-state]').click()

    // start to populate it and press save
    cy.get('[data-cy=search-team-lead]').type('jim')
    cy.get('[data-cy=potential-team-lead-2]').click()

    cy.changePermission(1, 300)

    cy.visit('/1/teams/1')

    cy.get('[data-cy=display-remove-team-lead-modal]').should('not.exist')
  })
})
